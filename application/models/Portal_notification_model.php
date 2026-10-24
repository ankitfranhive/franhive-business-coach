<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal_notification_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensure_schema();
    }

    private function ensure_schema()
    {
        if ($this->db->table_exists('PORTAL_USER_NOTIFICATIONS')) {
            return;
        }
        $this->db->query("CREATE TABLE IF NOT EXISTS PORTAL_USER_NOTIFICATIONS (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id INT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NULL,
            link_url VARCHAR(512) NULL,
            source_type VARCHAR(40) NULL,
            source_id INT UNSIGNED NULL,
            is_read TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL,
            PRIMARY KEY (id),
            KEY idx_user_read (user_id, is_read),
            KEY idx_source (source_type, source_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    }

    public function create_notification($user_id, $title, $message, $link_url = null, $source_type = null, $source_id = null)
    {
        $user_id = (int)$user_id;
        if ($user_id <= 0) {
            return false;
        }
        if ($source_type !== null && $source_id !== null) {
            $exists = $this->db->where('user_id', $user_id)
                ->where('source_type', $source_type)
                ->where('source_id', (int)$source_id)
                ->where('is_read', 0)
                ->count_all_results('PORTAL_USER_NOTIFICATIONS');
            if ($exists > 0) {
                return true;
            }
        }
        return $this->db->insert('PORTAL_USER_NOTIFICATIONS', [
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
            'link_url' => $link_url,
            'source_type' => $source_type,
            'source_id' => $source_id !== null ? (int)$source_id : null,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function count_unread($user_id)
    {
        return (int)$this->db->where('user_id', (int)$user_id)->where('is_read', 0)
            ->count_all_results('PORTAL_USER_NOTIFICATIONS');
    }

    public function get_unread_for_user($user_id, $limit = 15)
    {
        return $this->db->where('user_id', (int)$user_id)
            ->order_by('created_at', 'DESC')
            ->limit((int)$limit)
            ->get('PORTAL_USER_NOTIFICATIONS')
            ->result_array();
    }

    public function mark_read($id, $user_id)
    {
        $this->db->where('id', (int)$id)->where('user_id', (int)$user_id)
            ->update('PORTAL_USER_NOTIFICATIONS', ['is_read' => 1]);
    }
}
