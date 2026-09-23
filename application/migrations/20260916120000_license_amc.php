<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_License_amc extends CI_Migration
{
    public function up()
    {
        $this->load->model('License_model');
        $this->License_model->ensure_schema();
    }

    public function down()
    {
        $tables = array(
            'lic_heartbeat_log',
            'lic_integrity_log',
            'lic_file_baselines',
            'lic_audit_log',
            'lic_notification_log',
            'lic_notification_contacts',
            'lic_licenses',
            'lic_clients',
            'lic_vendor_users',
        );
        foreach ($tables as $table) {
            $this->dbforge->drop_table($table, true);
        }
    }
}
