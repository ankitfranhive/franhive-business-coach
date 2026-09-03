<?php
class LeadTaskModel extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function add_task_lead($data)
    {
        // Insert the task into the database
        $this->db->insert('LEAD_TASKS', $data);
        // Get the inserted task ID
        $task_id = $this->db->insert_id();
        
        // Fetch the inserted task with additional information
        $query = $this->db->query("
            SELECT LT.*, U.NAME AS ASSIGNED_TO_USER
            FROM LEAD_TASKS LT
            LEFT JOIN USERS U ON LT.ASSIGN_TO = U.USER_ID
            WHERE LT.TASK_ID = ?
        ", array($task_id));
        
        return $query->row_array();
    }




    public function add_task($data)
    {
        // Insert the task into the database
        $this->db->insert('FD_TASKS', $data);
        // Get the inserted task ID
        $task_id = $this->db->insert_id();
        
        // Fetch the inserted task with additional information
        $query = $this->db->query("
            SELECT LT.*, U.NAME AS ASSIGNED_TO_USER
            FROM FD_TASKS LT
            LEFT JOIN USERS U ON LT.ASSIGN_TO = U.USER_ID
            WHERE LT.TASK_ID = ?
        ", array($task_id));
        
        return $query->row_array();
    }

    public function update_lead_task($id, $data)
    {
        $this->db->where('TASK_ID', $id);
        $this->db->update('LEAD_TASKS', $data);
    }

    public function update_task($id, $data)
    {
        $this->db->where('TASK_ID', $id);
        $this->db->update('FD_TASKS', $data);
    }



    public function delete_task($id)
    {
        $this->db->where('TASK_ID', $id);
        $this->db->delete('FD_TASKS');
    }

    public function get_task_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('FD_TASKS');
        $this->db->where('TASK_ID', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_lead_task_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('LEAD_TASKS');
        $this->db->where('TASK_ID', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_client_task_list($user_id, $user_role)
    {
        // echo "here";
        // echo $user_role;

        if ($user_role == 'Admin') {
            // Fetch all tasks
            // echo "here11";
            $query = $this->db->query("
            SELECT LT.*, U.NAME AS ASSIGNED_TO_USER
            FROM FD_TASKS LT
            LEFT JOIN ENTITY U ON LT.ASSIGN_TO = U.ENTITY_ID
            WHERE LT.RECORD_STATUS = 1
            ORDER BY LT.CREATED_ON DESC
        ");

            // $query = $this->db->query("SELECT E.ENTITY_ID,E.NAME,TASK_ID,SUBJECT, OPTION_VALUE AS STATUS, U.NAME ACTIVITY_OWNER, FDT.START_DATE ACTIVITY_DATE  FROM LEAD_TASKS FDT LEFT JOIN ENTITY E ON E.ENTITY_ID = FDT.ENTITY_ID  LEFT JOIN ENTITY U ON FDT.ASSIGN_TO = U.ENTITY_ID LEFT JOIN COMMON_DROPDOWNS CD ON FDT.STATUS = CD.OPTION_ID AND DROPDOWN_TYPE = 'taskstatus'  WHERE  FDT.RECORD_STATUS = 0 AND IS_LEAD = 'Y'   ORDER BY FDT.CREATED_ON DESC");
            // return $query->result_array();
        } else {
            // echo "here22";
            // Fetch tasks assigned to the user
            $query = $this->db->query("
            SELECT LT.*, U.NAME AS ASSIGNED_TO_USER
            FROM FD_TASKS LT
            LEFT JOIN ENTITY U ON LT.ASSIGN_TO = U.ENTITY_ID
            WHERE LT.RECORD_STATUS = 1 AND LT.ASSIGN_TO = $user_id
            ORDER BY LT.CREATED_ON DESC
        ", array($user_id));
        }

        // die;
        return $query->result_array();
    }

    public function get_task_list($user_id, $user_role)
    {
        // echo "here";
        // echo $user_role;die;

        if ($user_role == 'Admin') {
            // Fetch all tasks
            // echo "here11";
            $query = $this->db->query("
            SELECT LT.*, U.NAME AS ASSIGNED_TO_USER
            FROM LEAD_TASKS LT
            LEFT JOIN ENTITY U ON LT.ASSIGN_TO = U.ENTITY_ID
            WHERE LT.RECORD_STATUS = 1
            ORDER BY LT.CREATED_ON DESC
        ");

            // $query = $this->db->query("SELECT E.ENTITY_ID,E.NAME,TASK_ID,SUBJECT, OPTION_VALUE AS STATUS, U.NAME ACTIVITY_OWNER, FDT.START_DATE ACTIVITY_DATE  FROM LEAD_TASKS FDT LEFT JOIN ENTITY E ON E.ENTITY_ID = FDT.ENTITY_ID  LEFT JOIN ENTITY U ON FDT.ASSIGN_TO = U.USER_ID LEFT JOIN COMMON_DROPDOWNS CD ON FDT.STATUS = CD.OPTION_ID AND DROPDOWN_TYPE = 'taskstatus'  WHERE  FDT.RECORD_STATUS = 0 AND IS_LEAD = 'Y'   ORDER BY FDT.CREATED_ON DESC");
            return $query->result_array();
        } else {
            // echo "here22";
            // Fetch tasks assigned to the user
            $query = $this->db->query("
            SELECT LT.*, U.NAME AS ASSIGNED_TO_USER
            FROM LEAD_TASKS LT
            LEFT JOIN ENTITY U ON LT.ASSIGN_TO = U.ENTITY_ID
            WHERE LT.RECORD_STATUS = 1 AND LT.ASSIGN_TO = $user_id
            ORDER BY LT.CREATED_ON DESC
        ", array($user_id));
        }

        // die;
        return $query->result_array();
    }
}
