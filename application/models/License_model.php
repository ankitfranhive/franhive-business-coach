<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class License_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_schema();
    }

    private static $schemaReady = false;

    public function ensure_schema()
    {
        if (self::$schemaReady) {
            return;
        }
        self::$schemaReady = true;
        $queries = array(
            "CREATE TABLE IF NOT EXISTS `lic_clients` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `client_name` VARCHAR(191) NOT NULL,
                `client_code` VARCHAR(64) NOT NULL,
                `status` ENUM('active','grace','suspended','cancelled') NOT NULL DEFAULT 'active',
                `notes` TEXT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `client_code` (`client_code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_licenses` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `client_id` INT UNSIGNED NOT NULL,
                `plan_name` VARCHAR(120) NOT NULL DEFAULT '',
                `start_date` DATE NOT NULL,
                `expiry_date` DATE NOT NULL,
                `grace_period_days` INT NOT NULL DEFAULT 3,
                `status` ENUM('active','grace','suspended','cancelled') NOT NULL DEFAULT 'active',
                `signed_token` TEXT NULL,
                `token_issued_at` DATETIME NULL,
                `created_by` INT UNSIGNED NULL,
                `updated_at` DATETIME NULL,
                PRIMARY KEY (`id`),
                KEY `client_id` (`client_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_notification_log` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `license_id` INT UNSIGNED NOT NULL,
                `notification_type` VARCHAR(32) NOT NULL,
                `sent_at` DATETIME NOT NULL,
                `channel` VARCHAR(16) NOT NULL DEFAULT 'email',
                `recipient` VARCHAR(191) NOT NULL DEFAULT '',
                `status` VARCHAR(32) NOT NULL DEFAULT 'sent',
                PRIMARY KEY (`id`),
                KEY `license_type` (`license_id`,`notification_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_notification_contacts` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `client_id` INT UNSIGNED NOT NULL,
                `name` VARCHAR(191) NOT NULL DEFAULT '',
                `email` VARCHAR(191) NOT NULL DEFAULT '',
                `phone` VARCHAR(64) NOT NULL DEFAULT '',
                `role_label` VARCHAR(80) NOT NULL DEFAULT '',
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                PRIMARY KEY (`id`),
                KEY `client_id` (`client_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_audit_log` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `actor` INT UNSIGNED NULL,
                `action` VARCHAR(64) NOT NULL,
                `target_client_id` INT UNSIGNED NULL,
                `details` TEXT NULL,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_integrity_log` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `check_time` DATETIME NOT NULL,
                `file_path` VARCHAR(255) NOT NULL,
                `expected_hash` VARCHAR(64) NOT NULL DEFAULT '',
                `actual_hash` VARCHAR(64) NOT NULL DEFAULT '',
                `match` TINYINT(1) NOT NULL DEFAULT 0,
                `alert_sent` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_heartbeat_log` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `client_id` INT UNSIGNED NULL,
                `checked_at` DATETIME NOT NULL,
                `license_status_at_check` VARCHAR(32) NOT NULL DEFAULT '',
                `file_hash_at_check` VARCHAR(64) NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_vendor_users` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `email` VARCHAR(191) NOT NULL,
                `password_hash` VARCHAR(255) NOT NULL,
                `name` VARCHAR(120) NOT NULL DEFAULT 'Vendor',
                `is_active` TINYINT(1) NOT NULL DEFAULT 1,
                `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
            "CREATE TABLE IF NOT EXISTS `lic_file_baselines` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `file_path` VARCHAR(255) NOT NULL,
                `expected_hash` VARCHAR(64) NOT NULL,
                `sealed_at` DATETIME NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `file_path` (`file_path`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        );
        foreach ($queries as $sql) {
            $this->db->query($sql);
        }
        $this->seed_vendor_user();
    }

    protected function seed_vendor_user()
    {
        $n = (int)$this->db->count_all('lic_vendor_users');
        if ($n > 0) {
            return;
        }
        $this->load->config('license');
        $email = (string)$this->config->item('license_vendor_email');
        $hash = (string)$this->config->item('license_vendor_password_hash');
        if ($email === '' || $hash === '') {
            return;
        }
        $this->db->insert('lic_vendor_users', array(
            'email' => $email,
            'password_hash' => $hash,
            'name' => 'Vendor',
            'is_active' => 1,
        ));
    }

    public function find_vendor_by_email($email)
    {
        return $this->db->get_where('lic_vendor_users', array('email' => $email, 'is_active' => 1), 1)->row_array();
    }

    public function get_clients_with_license()
    {
        $sql = "SELECT c.*, l.id AS license_id, l.plan_name, l.start_date, l.expiry_date,
                       l.grace_period_days, l.status AS license_status, l.signed_token, l.token_issued_at
                FROM lic_clients c
                LEFT JOIN lic_licenses l ON l.id = (
                    SELECT l2.id FROM lic_licenses l2 WHERE l2.client_id = c.id ORDER BY l2.id DESC LIMIT 1
                )
                ORDER BY c.id DESC";
        return $this->db->query($sql)->result_array();
    }

    public function get_client($id)
    {
        return $this->db->get_where('lic_clients', array('id' => (int)$id), 1)->row_array();
    }

    public function get_client_by_code($code)
    {
        return $this->db->get_where('lic_clients', array('client_code' => $code), 1)->row_array();
    }

    public function save_client($data, $id = 0)
    {
        if ($id) {
            $this->db->where('id', (int)$id)->update('lic_clients', $data);
            return (int)$id;
        }
        $this->db->insert('lic_clients', $data);
        return (int)$this->db->insert_id();
    }

    public function latest_license($client_id)
    {
        return $this->db->order_by('id', 'DESC')->get_where('lic_licenses', array('client_id' => (int)$client_id), 1)->row_array();
    }

    public function get_license($id)
    {
        return $this->db->get_where('lic_licenses', array('id' => (int)$id), 1)->row_array();
    }

    public function current_install_license()
    {
        $this->load->config('license');
        $configured = trim((string)$this->config->item('license_client_code'));
        $sub = defined('SUBDOMAIN') ? trim((string)SUBDOMAIN) : '';
        $codes = array();
        if ($configured !== '') {
            $codes[] = $configured;
        }
        if ($sub !== '') {
            $codes[] = $sub;
        }

        $client = null;
        foreach ($codes as $code) {
            $client = $this->get_client_by_code($code);
            if ($client) {
                break;
            }
        }
        if (!$client) {
            $rows = $this->db->order_by('id', 'ASC')->get('lic_clients')->result_array();
            if (count($rows) === 1) {
                $client = $rows[0];
            } elseif ($sub !== '' && $rows) {
                foreach ($rows as $row) {
                    $code = strtolower((string)$row['client_code']);
                    if ($code === strtolower($sub) || strpos($code, strtolower($sub) . '-') === 0) {
                        $client = $row;
                        break;
                    }
                }
                if (!$client) {
                    $client = $rows[0];
                }
            } elseif ($rows) {
                $client = $rows[0];
            }
        }
        if (!$client) {
            return null;
        }
        $license = $this->latest_license($client['id']);
        if (!$license) {
            return null;
        }
        $license['client_code'] = $client['client_code'];
        $license['client_name'] = $client['client_name'];
        $license['client_status'] = $client['status'];
        return $license;
    }

    public function current_install_fingerprint()
    {
        $row = $this->current_install_license();
        if (!$row) {
            return 'none';
        }
        return sha1(
            (string)($row['id'] ?? '') . '|' .
            (string)($row['status'] ?? '') . '|' .
            (string)($row['expiry_date'] ?? '') . '|' .
            (string)($row['grace_period_days'] ?? '') . '|' .
            (string)($row['token_issued_at'] ?? '')
        );
    }

    public function save_license($data, $id = 0)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        if ($id) {
            $this->db->where('id', (int)$id)->update('lic_licenses', $data);
            return (int)$id;
        }
        $this->db->insert('lic_licenses', $data);
        return (int)$this->db->insert_id();
    }

    public function contacts($client_id, $active_only = false)
    {
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        return $this->db->order_by('id', 'DESC')->get_where('lic_notification_contacts', array('client_id' => (int)$client_id))->result_array();
    }

    public function save_contact($data, $id = 0)
    {
        if ($id) {
            $this->db->where('id', (int)$id)->update('lic_notification_contacts', $data);
            return (int)$id;
        }
        $this->db->insert('lic_notification_contacts', $data);
        return (int)$this->db->insert_id();
    }

    public function delete_contact($id)
    {
        $this->db->where('id', (int)$id)->delete('lic_notification_contacts');
    }

    public function notification_exists($license_id, $type, $cycle_start)
    {
        $row = $this->db->query(
            "SELECT id FROM lic_notification_log
             WHERE license_id = ? AND notification_type = ? AND sent_at >= ?
             LIMIT 1",
            array((int)$license_id, $type, $cycle_start)
        )->row_array();
        return !empty($row);
    }

    public function log_notification($license_id, $type, $channel, $recipient, $status)
    {
        $this->db->insert('lic_notification_log', array(
            'license_id' => (int)$license_id,
            'notification_type' => $type,
            'sent_at' => date('Y-m-d H:i:s'),
            'channel' => $channel,
            'recipient' => $recipient,
            'status' => $status,
        ));
    }

    public function notification_logs($limit = 100)
    {
        $sql = "SELECT n.*, c.client_name, l.expiry_date
                FROM lic_notification_log n
                LEFT JOIN lic_licenses l ON l.id = n.license_id
                LEFT JOIN lic_clients c ON c.id = l.client_id
                ORDER BY n.id DESC LIMIT " . (int)$limit;
        return $this->db->query($sql)->result_array();
    }

    public function audit($actor, $action, $client_id, $details)
    {
        $this->db->insert('lic_audit_log', array(
            'actor' => $actor ? (int)$actor : null,
            'action' => $action,
            'target_client_id' => $client_id ? (int)$client_id : null,
            'details' => is_string($details) ? $details : json_encode($details),
            'created_at' => date('Y-m-d H:i:s'),
        ));
    }

    public function get_baselines()
    {
        $rows = $this->db->get('lic_file_baselines')->result_array();
        $out = array();
        foreach ($rows as $row) {
            $out[$row['file_path']] = $row;
        }
        return $out;
    }

    public function seal_baseline($file_path, $hash)
    {
        $now = date('Y-m-d H:i:s');
        $existing = $this->db->get_where('lic_file_baselines', array('file_path' => $file_path), 1)->row_array();
        if ($existing) {
            $this->db->where('id', $existing['id'])->update('lic_file_baselines', array(
                'expected_hash' => $hash,
                'sealed_at' => $now,
            ));
            return;
        }
        $this->db->insert('lic_file_baselines', array(
            'file_path' => $file_path,
            'expected_hash' => $hash,
            'sealed_at' => $now,
        ));
    }

    public function log_integrity($file_path, $expected, $actual, $match, $alert_sent)
    {
        $this->db->insert('lic_integrity_log', array(
            'check_time' => date('Y-m-d H:i:s'),
            'file_path' => $file_path,
            'expected_hash' => $expected,
            'actual_hash' => $actual,
            'match' => $match ? 1 : 0,
            'alert_sent' => $alert_sent ? 1 : 0,
        ));
    }

    public function integrity_logs($limit = 100)
    {
        return $this->db->order_by('id', 'DESC')->get('lic_integrity_log', (int)$limit)->result_array();
    }

    public function log_heartbeat($client_id, $status, $hash)
    {
        $this->db->insert('lic_heartbeat_log', array(
            'client_id' => $client_id ? (int)$client_id : null,
            'checked_at' => date('Y-m-d H:i:s'),
            'license_status_at_check' => $status,
            'file_hash_at_check' => $hash,
        ));
    }

    public function heartbeat_logs($limit = 50)
    {
        return $this->db->order_by('id', 'DESC')->get('lic_heartbeat_log', (int)$limit)->result_array();
    }

    public function active_licenses()
    {
        $sql = "SELECT l.*, c.client_name, c.client_code, c.status AS client_status
                FROM lic_licenses l
                INNER JOIN lic_clients c ON c.id = l.client_id
                WHERE l.id IN (SELECT MAX(id) FROM lic_licenses GROUP BY client_id)
                ORDER BY l.expiry_date ASC";
        return $this->db->query($sql)->result_array();
    }
}
