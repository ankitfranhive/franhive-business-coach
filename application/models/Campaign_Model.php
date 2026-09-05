<?php

class Campaign_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->ensure_schema();
    }

    private function ensure_schema()
    {
        $needed = [
            'CAMPAIGN' => [
                'WORKFLOW_STATUS' => "ALTER TABLE CAMPAIGN ADD COLUMN WORKFLOW_STATUS VARCHAR(32) NULL DEFAULT 'draft'",
                'TOTAL_RECIPIENTS' => "ALTER TABLE CAMPAIGN ADD COLUMN TOTAL_RECIPIENTS INT NULL DEFAULT 0",
                'SENT_COUNT' => "ALTER TABLE CAMPAIGN ADD COLUMN SENT_COUNT INT NULL DEFAULT 0",
                'LAST_ACTIVITY_AT' => "ALTER TABLE CAMPAIGN ADD COLUMN LAST_ACTIVITY_AT DATETIME NULL",
                'TIMEZONE' => "ALTER TABLE CAMPAIGN ADD COLUMN TIMEZONE VARCHAR(64) NULL DEFAULT 'Australia/Melbourne'",
            ],
            'TEMPLATES' => [
                'MERGE_TAGS' => "ALTER TABLE TEMPLATES ADD COLUMN MERGE_TAGS TEXT NULL",
            ],
            'CAMPAIGN_TEMPLATES_MAPPING' => [
                'SEND_STATUS' => "ALTER TABLE CAMPAIGN_TEMPLATES_MAPPING ADD COLUMN SEND_STATUS VARCHAR(32) NULL DEFAULT 'pending'",
                'SENT_AT' => "ALTER TABLE CAMPAIGN_TEMPLATES_MAPPING ADD COLUMN SENT_AT DATETIME NULL",
                'SEND_AT' => "ALTER TABLE CAMPAIGN_TEMPLATES_MAPPING ADD COLUMN SEND_AT DATETIME NULL",
                'TIMEZONE' => "ALTER TABLE CAMPAIGN_TEMPLATES_MAPPING ADD COLUMN TIMEZONE VARCHAR(64) NULL DEFAULT 'Australia/Melbourne'",
            ],
            'CAMPAIGN_TEMPLATE_USER_MAPPING' => [
                'CONTACT_SOURCE' => "ALTER TABLE CAMPAIGN_TEMPLATE_USER_MAPPING ADD COLUMN CONTACT_SOURCE VARCHAR(20) NULL DEFAULT 'entity'",
            ],
        ];
        foreach ($needed as $table => $cols) {
            foreach ($cols as $column => $sql) {
                if (!$this->column_exists($table, $column)) {
                    $this->db->query($sql);
                }
            }
        }
    }

    private function column_exists($table, $column)
    {
        $query = $this->db->query('SHOW COLUMNS FROM `' . $table . '` LIKE ' . $this->db->escape($column));
        return $query && $query->num_rows() > 0;
    }


    public function get_all_campaigns()
    {

        $query = $this->db->query("SELECT CAMPAIGN_ID,TITLE,DESCRIPTION,MODULE_NAME,SUB_MODULE_NAME,START_DATE,END_DATE,STATUS,WORKFLOW_STATUS,TOTAL_RECIPIENTS,SENT_COUNT FROM CAMPAIGN WHERE 1=1 AND RECORD_STATUS = 0 order by CAMPAIGN_ID DESC");

        return $query->result_array();
    }

    public function get_all_new_campaigns()
    {

        $query = $this->db->query("SELECT CAMPAIGN_ID,TITLE,DESCRIPTION,MODULE_NAME,SUB_MODULE_NAME,START_DATE,END_DATE,REPLY_ADDRESS ,`STATUS`
                                   FROM CAMPAIGN WHERE  RECORD_STATUS = 0 AND (`STATUS` =1 OR `STATUS` ='C') order by CAMPAIGN_ID DESC;");

        return $query->result_array();
    }

    public function get_lead_campaign_schduled()
    {
        $current_date = date('Y-m-d H:i:s');
        $query = $this->db->query("
            SELECT CTM.SEND_DATE, CTM.TEMPLATE_START_TIME, C.TITLE, T.TEMPLATE_NAME, E.ENTITY_ID, E.NAME 
            FROM CAMPAIGN_ASSOCIATION_MAPPING CAM 
            LEFT JOIN CAMPAIGN_TEMPLATES_MAPPING CTM ON CAM.CAMPAIGN_ID = CTM.CAMPAIGN_ID 
            LEFT JOIN CAMPAIGN C ON C.CAMPAIGN_ID = CTM.CAMPAIGN_ID 
            LEFT JOIN TEMPLATES T ON T.TEMPLATE_ID = CTM.TEMPLATE_ID 
            LEFT JOIN ENTITY E ON E.ENTITY_ID = CAM.FOREIGN_ID 
            WHERE E.RECORD_STATUS = 0 AND IS_LEAD = 'Y' AND CTM.SEND_DATE >= '2022-04-24 00:44:40' 
            AND CTM.SEND_DATE <= '$current_date' 
            ORDER BY CTM.SEND_DATE ASC 
            LIMIT 100
        ");
        return $query->result_array();
    }


    public function get_client_campaign_schduled()
    {

        $query = $this->db->query("SELECT CTM.SEND_DATE, CTM.TEMPLATE_START_TIME, C.TITLE, T.TEMPLATE_NAME, E.ENTITY_ID, E.STORE_NAME 
        FROM CAMPAIGN_ASSOCIATION_MAPPING CAM 
        LEFT JOIN CAMPAIGN_TEMPLATES_MAPPING CTM ON CAM.CAMPAIGN_ID = CTM.CAMPAIGN_ID 
        LEFT JOIN CAMPAIGN C ON C.CAMPAIGN_ID = CTM.CAMPAIGN_ID
        LEFT JOIN TEMPLATES T ON T.TEMPLATE_ID = CTM.TEMPLATE_ID
        LEFT JOIN ENTITY E ON E.ENTITY_ID = CAM.FOREIGN_ID 
        WHERE  E.RECORD_STATUS = 0 AND IS_FRANCHISEE = 'Y' 
        AND CTM.SEND_DATE >= '2022-04-24 00:44:40' AND CTM.SEND_DATE<= '2026-04-24 00:44:40' 
        ORDER BY CTM.SEND_DATE ASC LIMIT 100");

        return $query->result_array();
    }

    public function get_client_campaign_history()
    {

        $query = $this->db->query("SELECT E.ENTITY_ID, E.STORE_NAME, CEL.ID,E.NAME,CEL.CAMPAIGN_ID, CEL.SUBJECT, CEL.TO_EMAIL, U.NAME ACTIVITY_OWNER, CEL.SEND_DATE ACTIVITY_DATE,CEL.DELIVER_DATE,C.TITLE 
        FROM CAMPAIGN_EMAIL_LOGS CEL 
        LEFT JOIN CAMPAIGN C ON C.CAMPAIGN_ID = CEL.CAMPAIGN_ID 
        LEFT JOIN ENTITY E ON E.ENTITY_ID = CEL.FOREIGN_ID 
        LEFT JOIN USERS U ON C.ADDED_BY = U.USER_ID 
        WHERE  E.RECORD_STATUS = 0 AND IS_FRANCHISEE = 'Y' AND CEL.SEND_DATE >= '2022-04-24 00:44:40' AND CEL.SEND_DATE<= '2026-04-24 00:44:40'  ORDER BY CEL.SEND_DATE DESC LIMIT 100");

        return $query->result_array();
    }

    public function get_lead_campaign_history()
    {

        $query = $this->db->query("SELECT E.ENTITY_ID, E.NAME, CEL.ID,E.NAME,CEL.CAMPAIGN_ID, CEL.SUBJECT, CEL.TO_EMAIL, U.NAME ACTIVITY_OWNER, CEL.SEND_DATE ACTIVITY_DATE,CEL.DELIVER_DATE,C.TITLE
        FROM CAMPAIGN_EMAIL_LOGS CEL 
        LEFT JOIN CAMPAIGN C ON C.CAMPAIGN_ID = CEL.CAMPAIGN_ID 
        LEFT JOIN ENTITY E ON E.ENTITY_ID = CEL.FOREIGN_ID 
        LEFT JOIN USERS U ON C.ADDED_BY = U.USER_ID 
        WHERE  E.RECORD_STATUS = 0 AND IS_LEAD = 'Y' 
        ORDER BY CEL.SEND_DATE DESC LIMIT 100");

        return $query->result_array();
    }



    public function create_new_campaign($data)
    {
        // Insert the campaign data into the CAMPAIGN table
        $this->db->insert('CAMPAIGN', $data);

        // Retrieve and return the ID of the newly inserted campaign
        return $this->db->insert_id();
    }


    public function get_campaign_by_id($campaign_id)
    {
        // Retrieve course data from the database based on $campaign_id
        $query = $this->db->get_where('CAMPAIGN', array('CAMPAIGN_ID' => $campaign_id));
        return $query->row_array();
    }

    public function get_active_campaign_count()
    {
        // Select the count of rows where the STATUS is 1
        $this->db->where('STATUS', 1);
        $this->db->from('CAMPAIGN');
        $count = $this->db->count_all_results();

        return $count;
    }

    public function get_completed_campaign_count()
    {
        // Select the count of rows where the STATUS is 1
        $this->db->where('STATUS', 0);
        $this->db->from('CAMPAIGN');
        $count = $this->db->count_all_results();

        return $count;
    }


    public function get_campaigns_list($limit = 10, $status = 0)
    {
        // Order by CREATED_AT in descending order to get the most recent entries first
        $this->db->where('STATUS', $status);

        $this->db->order_by('CREATED_ON', 'DESC');

        // Limit the number of results returned (optional)
        $this->db->limit($limit);

        // Retrieve the data from the CAMPAIGN table
        $query = $this->db->get('CAMPAIGN');

        // Return the result as an array
        return $query->result_array();
    }


    public function update_campaign($CAMPAIGN_ID, $data)
    {
        // Update the course data in the database
        $this->db->where('CAMPAIGN_ID', $CAMPAIGN_ID);
        $this->db->update('CAMPAIGN', $data);
    }

    public function get_all_templates()
    {
        $query = $this->db->query("
            SELECT TEMPLATE_SUBJECT, TEMPLATE_NAME, MODULE_NAME, TEMPLATE_ID, MERGE_TAGS
            FROM TEMPLATES
            WHERE (RECORD_STATUS = 0 OR RECORD_STATUS IS NULL)
              AND TRIM(MODULE_NAME) IN ('Lead', 'Client', 'Campaign')
            ORDER BY TEMPLATE_ID DESC
        ");

        return $query->result_array();
    }

    /**
     * Email templates for payment agreement sends (Payment Agreement module only).
     */
    public function get_templates_for_payment_agreement()
    {
        $this->db->select('TEMPLATE_ID, TEMPLATE_NAME, TEMPLATE_SUBJECT, MODULE_NAME');
        $this->db->from('TEMPLATES');
        $this->db->where('(RECORD_STATUS = 0 OR RECORD_STATUS IS NULL)', null, false);
        $this->db->where("TRIM(MODULE_NAME) = 'Payment Agreement'", null, false);
        $this->db->order_by('TEMPLATE_NAME', 'ASC');
        return $this->db->get()->result_array();
    }
    


    public function create_new_template($data)
    {
        // Insert the course data into the TEMPLATE table
        $this->db->insert('TEMPLATES', $data);
    }

    public function get_template_by_id($teamplate_id)
    {
        // Retrieve course data from the database based on $teamplate_id
        $query = $this->db->get_where('TEMPLATES', array('TEMPLATE_ID' => $teamplate_id));
        return $query->row_array();
    }

    public function get_template_campaign_mapping_id($camp_id)
    {
        // Get the current date and time
        $currentDateTime = date('Y-m-d H'); // Format: 'YYYY-MM-DD HH'
    
        // Combine date and time fields in the query
        $this->db->where('CAMPAIGN_ID', $camp_id);
        $this->db->where("CONCAT(SEND_DATE, ' ', LPAD(TEMPLATE_START_TIME, 2, '0')) <=", $currentDateTime);
    
        $query = $this->db->get('CAMPAIGN_TEMPLATES_MAPPING');
    
        // Print the query for debugging
        // echo $this->db->last_query();die;
    
        return $query->result_array();
    }
    
    

    public function update_template($template_id, $data)
    {
        // If database is auto-loaded, you don't even need this line:
        // $this->load->database();
    
        // Connection & DB are already utf8mb4 from database.php, no need to SET again
    
        $this->db->where('TEMPLATE_ID', $template_id);
        $this->db->update('TEMPLATES', $data);
    
        return $this->db->affected_rows(); // optional: to know if something changed
    }
    

    public function delete_campaign($CAMPAIGN_ID)
    {
        // Update the course data in the database
        $this->db->where('CAMPAIGN_ID', $CAMPAIGN_ID);
        $this->db->delete('CAMPAIGN');
    }
    public function delete_template($teamplate_id)
    {
        // Update the course data in the database
        $this->db->where('TEMPLATE_ID', $teamplate_id);
        $this->db->delete('TEMPLATES');
    }

    public function delete_campaign_templates($CAMPAIGN_ID)
    {
        $this->db->where('CAMPAIGN_ID', $CAMPAIGN_ID);
        $this->db->delete('CAMPAIGN_TEMPLATES_MAPPING');
    }

    public function insert_campaign_template($data)
    {
        $this->db->insert('CAMPAIGN_TEMPLATES_MAPPING', $data);
    }

    public function get_selected_templates($CAMPAIGN_ID)
    {
        $this->db->where('CAMPAIGN_ID', $CAMPAIGN_ID);
        $query = $this->db->get('CAMPAIGN_TEMPLATES_MAPPING');
        return $query->result_array();
    }

    public function get_selected_users($campaign_id)
    {
        $this->db->select('USER_ID');
        $this->db->from('CAMPAIGN_TEMPLATE_USER_MAPPING');
        $this->db->where('CAMPAIGN_ID', $campaign_id);
        $query = $this->db->get();
        return $query->result_array();
    }


    public function delete_campaign_users($campaign_id)
    {
        $this->db->where('CAMPAIGN_ID', $campaign_id);
        $this->db->delete('CAMPAIGN_TEMPLATE_USER_MAPPING');
    }

    public function add_campaign_user($data)
    {
        $this->db->insert('CAMPAIGN_TEMPLATE_USER_MAPPING', $data);
    }

    public function get_users_by_campiagn_id($campaign_id)
    {
        $sql = "
            SELECT ENTITY.EMAIL as email_id, ENTITY.NAME as user_name, ENTITY.ENTITY_ID,
                   ENTITY.EMAIL, ENTITY.NAME, ENTITY.MOBILE, ENTITY.PHONE, ENTITY.COMPANY, ENTITY.STORE_NAME, ENTITY.CITY
            FROM CAMPAIGN_TEMPLATE_USER_MAPPING
            INNER JOIN ENTITY ON CAMPAIGN_TEMPLATE_USER_MAPPING.USER_ID = ENTITY.ENTITY_ID
            WHERE CAMPAIGN_TEMPLATE_USER_MAPPING.CAMPAIGN_ID = ?
              AND (CAMPAIGN_TEMPLATE_USER_MAPPING.CONTACT_SOURCE IS NULL
                   OR CAMPAIGN_TEMPLATE_USER_MAPPING.CONTACT_SOURCE = ''
                   OR CAMPAIGN_TEMPLATE_USER_MAPPING.CONTACT_SOURCE = 'entity')
            UNION ALL
            SELECT USERS.EMAIL as email_id, USERS.NAME as user_name, USERS.USER_ID as ENTITY_ID,
                   USERS.EMAIL, USERS.NAME, USERS.MOBILE, USERS.MOBILE as PHONE, NULL as COMPANY, NULL as STORE_NAME, USERS.CITY
            FROM CAMPAIGN_TEMPLATE_USER_MAPPING
            INNER JOIN USERS ON CAMPAIGN_TEMPLATE_USER_MAPPING.USER_ID = USERS.USER_ID
            WHERE CAMPAIGN_TEMPLATE_USER_MAPPING.CAMPAIGN_ID = ?
              AND CAMPAIGN_TEMPLATE_USER_MAPPING.CONTACT_SOURCE = 'user'
        ";
        return $this->db->query($sql, [$campaign_id, $campaign_id])->result_array();
    }

    public function count_by_workflow($status)
    {
        $this->db->where('WORKFLOW_STATUS', $status);
        $this->db->from('CAMPAIGN');
        return (int)$this->db->count_all_results();
    }

    public function get_campaigns_by_workflow($status, $limit = 10)
    {
        $this->db->where('WORKFLOW_STATUS', $status);
        $this->db->order_by('CREATED_ON', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('CAMPAIGN')->result_array();
    }

    public function get_recent_campaigns($limit = 8)
    {
        $this->db->order_by('CREATED_ON', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('CAMPAIGN')->result_array();
    }

    public function get_due_template_mappings($campaign_id)
    {
        $now = date('Y-m-d H:i:s');
        $sql = "
            SELECT *
            FROM CAMPAIGN_TEMPLATES_MAPPING
            WHERE CAMPAIGN_ID = ?
              AND (SEND_STATUS = 'pending' OR SEND_STATUS IS NULL OR SEND_STATUS = '1')
              AND (
                    (SEND_AT IS NOT NULL AND SEND_AT <= ?)
                 OR (SEND_AT IS NULL AND CONCAT(SEND_DATE, ' ', LPAD(IFNULL(TEMPLATE_START_TIME, 0), 2, '0'), ':00:00') <= ?)
              )
            ORDER BY SENDING_ORDER ASC, ID ASC
        ";
        return $this->db->query($sql, [$campaign_id, $now, $now])->result_array();
    }

    public function mark_template_mapping_sent($mapping_id)
    {
        $this->db->where('ID', $mapping_id);
        $this->db->update('CAMPAIGN_TEMPLATES_MAPPING', [
            'SEND_STATUS' => 'sent',
            'SENT_AT' => date('Y-m-d H:i:s'),
        ]);
    }

    public function campaign_has_pending_emails($campaign_id)
    {
        $this->db->where('CAMPAIGN_ID', $campaign_id);
        $this->db->group_start();
        $this->db->where('SEND_STATUS', 'pending');
        $this->db->or_where('SEND_STATUS IS NULL', null, false);
        $this->db->group_end();
        $this->db->from('CAMPAIGN_TEMPLATES_MAPPING');
        return $this->db->count_all_results() > 0;
    }

    public function log_campaign_email($data)
    {
        $this->db->insert('CAMPAIGN_EMAIL_LOGS', $data);
    }

    public function refresh_campaign_counts($campaign_id)
    {
        $this->db->where('CAMPAIGN_ID', $campaign_id);
        $recipients = $this->db->count_all_results('CAMPAIGN_TEMPLATE_USER_MAPPING');
        $this->db->where('CAMPAIGN_ID', $campaign_id);
        $sent = $this->db->count_all_results('CAMPAIGN_EMAIL_LOGS');
        $this->update_campaign($campaign_id, [
            'TOTAL_RECIPIENTS' => $recipients,
            'SENT_COUNT' => $sent,
            'LAST_ACTIVITY_AT' => date('Y-m-d H:i:s'),
        ]);
    }

    public function set_workflow($campaign_id, $workflow)
    {
        $this->update_campaign($campaign_id, [
            'WORKFLOW_STATUS' => $workflow,
            'STATUS' => campaign_legacy_status($workflow),
            'MODIFIED_ON' => date('Y-m-d H:i:s'),
            'LAST_ACTIVITY_AT' => date('Y-m-d H:i:s'),
        ]);
    }

    public function get_schedulable_campaigns()
    {
        $this->db->where_in('WORKFLOW_STATUS', ['scheduled', 'in_progress']);
        $this->db->order_by('CAMPAIGN_ID', 'DESC');
        return $this->db->get('CAMPAIGN')->result_array();
    }
}
