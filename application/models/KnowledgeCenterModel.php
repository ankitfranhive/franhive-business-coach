<?php

class KnowledgeCenterModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); // Load the database library
    }


    public function get_all_training_courses()
    {

        $query = $this->db->query("SELECT * FROM COURSES WHERE 1=1 AND RECORD_STATUS = 0 ");

        return $query->result_array();
    }

    public function get_all_training_course_lessons()
    {

        $query = $this->db->query("SELECT LESSONS.* ,COURSES.NAME AS `COURSE_NAME`
                                    FROM LESSONS 
                                    LEFT JOIN COURSES ON COURSES.COURSE_ID = LESSONS.COURSE_ID
                                    ORDER BY LESSONS.LESSON_ID DESC"


    );
        return $query->result_array();
        
    }

    public function get_lesson_by_id($lessonId)
    {

        $query = $this->db->query("SELECT LESSONS.* ,COURSES.NAME AS `COURSE_NAME`
                                    FROM LESSONS 
                                    LEFT JOIN COURSES ON COURSES.COURSE_ID = LESSONS.COURSE_ID
                                    WHERE LESSON_ID = ".$lessonId."
                                    ORDER BY LESSONS.LESSON_ID DESC"


    );
        return $query->row_array();
        
    }

    public function create_course($data)
    {
        // Insert the course data into the COURSES table
        $this->db->insert('COURSES', $data);
    }

    public function get_course_by_id($course_id)
    {
        // Retrieve course data from the database based on $course_id
        $query = $this->db->get_where('COURSES', array('COURSE_ID' => $course_id));
        return $query->row_array();
    }

    public function update_course($course_id, $data)
    {
        // Update the course data in the database
        $this->db->where('COURSE_ID', $course_id);
        $this->db->update('COURSES', $data);

        // echo $this->db->last_query();die;
    }

    public function delete_course($course_id)
    {
        // Update the course data in the database
        $this->db->where('COURSE_ID', $course_id);
        $this->db->delete('COURSES');
    }

    public function create_course_lesson($data)
    {
        // Insert the course data into the COURSES table
        $this->db->insert('LESSONS', $data);
    }

    public function get_course_lesson_by_id($course_id)
    {
        // Retrieve course data from the database based on $course_id
        $query = $this->db->get_where('LESSONS', array('LESSON_ID' => $course_id));
        return $query->row_array();
    }

    public function update_course_lesson($course_id, $data)
    {
        // Update the course data in the database
        $this->db->where('LESSON_ID', $course_id);
        $this->db->update('LESSONS', $data);

        // echo $this->db->last_query();die;
    }

    

    public function delete_course_lesson($course_id)
    {
        // Update the course data in the database
        $this->db->where('LESSON_ID', $course_id);
        $this->db->delete('LESSONS');
    }


    public function get_userassigned_courses($user_id)
    {
        // Fetch questions from your database   

        $query = "SELECT COURSES.*, USER_COURSE_MAPPING.USER_ID,USER_COURSE_MAPPING.ID,USER_COURSE_MAPPING.IS_REMOVED
                    FROM COURSES 
                    LEFT JOIN USER_COURSE_MAPPING on USER_COURSE_MAPPING.COURSE_ID = COURSES.COURSE_ID AND USER_COURSE_MAPPING.USER_ID=".$user_id."
                    WHERE COURSES.RECORD_STATUS=0
                    ORDER BY COURSES.COURSE_ID DESC
                    ";

        $query = $this->db->query($query);
        return $query->result();
    }

    public function save_course_user_mapping($data)
    {
        // Insert the question data into the TEST_SERIES_QUESTION table
        $add_qustn =  $this->db->insert('USER_COURSE_MAPPING', $data);
        $this->db->last_query();
        if ($add_qustn) {
            return true;
        }
    }

    public function remove_user_course_mapping($id, $is_removed)
    {
        // Assuming $mapping_id is the ID of the mapping you want to remove

        $data = array(
            'is_removed' => $is_removed // Assuming 'is_removed' is the column you want to update
        );

        $this->db->where('ID', $id);
        $this->db->update('USER_COURSE_MAPPING', $data);

        // Check if the update was successful
        $affected_rows = $this->db->affected_rows();

        if ($affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function get_lessons_of_course($course_id)
    {

        $query = $this->db->query("SELECT * FROM LESSONS WHERE RECORD_STATUS = 0 AND COURSE_ID=".$course_id." ORDER BY LESSON_ID DESC");
        return $query->result_array();
        
    }
}
