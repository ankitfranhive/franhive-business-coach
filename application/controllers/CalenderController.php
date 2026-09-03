<?php
defined('BASEPATH') or exit('No direct script access allowed');

class CalenderController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('KnowledgeCenterModel');
        $this->load->model('LeadTaskModel');
        $this->load->model('User_Model');


        $this->load->model('Calendar_model');
        $this->load->library('session');

        // If you already have your own auth checks, keep them.
        if (!$this->session->userdata('user') || !isset($this->session->userdata('user')['USER_ID'])) {
            redirect('login');
        }
    }

    public function getHolidayCalender()
    {
        $userid = $this->session->userdata('user')['USER_ID'];
        $user_role = $this->User_Model->getUserRoles($userid);

        $data = $this->LeadTaskModel->get_task_list($userid, $user_role);
        $eventData['event_list'] = $this->convertToFullCalendarEvents($data);

        // print_r($eventData);
        // die;

        $this->load->view('calender/calendar_view');

        // $this->load->view('calender/lead_tasks_view', $eventData);
    }

    public function getClientTaskData()
    {
        $userid = $this->session->userdata('user')['USER_ID'];
        $user_role = $this->User_Model->getUserRoles($userid);

        // $data = $this->LeadTaskModel->get_task_list($userid, $user_role);
        // $eventData['event_list'] = $this->convertToFullCalendarEvents($data);
        $events = [];

        // Get the current date
        $currentDate = new DateTime();

        // Event 1: All Day Event
        $events[] = [
            'title' => 'All Day Test Event',
            'start' => $currentDate->format('Y-m-d')
        ];
        $eventData['event_list'] = $events;
        // print_r(json_encode($eventData['event_list']));
        // die;

        $this->load->view('calender/client_tasks_view', $eventData);
    }



    private function convertToFullCalendarEvents($tasks)
    {
        $events = [];

        foreach ($tasks as $key => $task) {
            // Extract date parts
            $start_date = date('Y-m-d', strtotime($task['START_DATE']));

            // Calculate time parts if they exist
            $start_time = isset($task['START_TIME']) ? date('H:i:s', strtotime("00:00:00 +{$task['START_TIME']} minutes")) : null;
            $end_time = isset($task['END_TIME']) ? date('H:i:s', strtotime("00:00:00 +{$task['END_TIME']} minutes")) : null;

            // Construct start and end date-time strings
            $start = $start_time ? "{$start_date}T{$start_time}+00:00" : $start_date;
            $end = $end_time ? "{$start_date}T{$end_time}+00:00" : $start_date;

            $event = [
                'title' => $task['SUBJECT'],
                'start' => $start,
                'end' => $end,
                'description' => $task['TASK_DESC']
            ];



            $events[] = $event;
        }

        return json_encode($events);
    }

    private function formatTime($timeIndex)
    {
        $hour = floor($timeIndex / 4);
        $minute = ($timeIndex % 4) * 15;
        $date = sprintf('%02d:%02d:00', $hour, $minute);
        return date('Y-m-d', strtotime($date));
    }


    public function getAllTrainingCourses()
    {

        $data['all_course'] = $this->KnowledgeCenterModel->get_all_training_courses();

        $this->load->view('knowlege_center/training_course_list', $data);
    }



    public function addTraingCourse()
    {
        $this->load->view('knowlege_center/add_training_course');
    }

    public function create_course()
    {
        // Retrieve form data using $this->input->post('field_name');
        $name = $this->input->post('name');
        $objective = $this->input->post('objective');
        $THUMNAIL_IMAGE = $this->input->post('THUMNAIL_IMAGE');

        // Save the data to the database using your model
        $data = array(
            'name' => $name,
            'objective' => $objective,
            'THUMNAIL_IMAGE' => $THUMNAIL_IMAGE
        );

        $this->KnowledgeCenterModel->create_course($data);

        redirect('knowledge-center');
    }


    public function editCourse($course_id)
    {
        // Load the course data based on the $course_id
        $course = $this->KnowledgeCenterModel->get_course_by_id($course_id);

        // Pass the course data to your view for editing
        // echo "<pre>";
        // print_r($course);die;
        $data['course_data'] = $course;
        $this->load->view('knowlege_center/edit_training_course', $data);
    }



    public function updateCourse()
    {
        // Create the uploads directory if it doesn't exist
        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        // Retrieve form data using $this->input->post('field_name');
        $course_id = $this->input->post('course_id');
        $name = $this->input->post('name');
        $objective = $this->input->post('objective');
        $thumbnail_image = "";
        // Check if a file was selected
        if (!empty($_FILES['cover_image']['name'])) {
            // File upload configuration
            $config['upload_path'] = $upload_path; // Specify the upload directory
            $config['allowed_types'] = 'gif|jpg|png'; // Specify allowed file types
            $config['max_size'] = 1024; // Specify max file size in KB

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('cover_image')) {
                // Handle upload failure
                $error = array('error' => $this->upload->display_errors());
                // var_dump( $error);die;
                // You can handle the error as per your application's requirements
            } else {
                // Upload successful, save the file URL to the database
                $data = array('upload_data' => $this->upload->data());
                $thumbnail_image = base_url() . 'uploads/' . $data['upload_data']['file_name'];

                // Save the updated data to the database using your model

            }
        }

        $data = array(
            'NAME' => $name,
            'OBJECTIVE' => $objective,
            'THUMNAIL_IMAGE' => $thumbnail_image
        );

        $this->KnowledgeCenterModel->update_course($course_id, $data);

        redirect('knowledge-center');
    }


    public function deleteCourse()
    {

        // Retrieve form data using $this->input->post('field_name');
        $course_id = $this->input->post('course_id');

        $this->KnowledgeCenterModel->delete_course($course_id);

        redirect('knowledge-center');
    }

    public function getAllTasks()
    {

        $data['all_task'] = $this->LeadModel->get_all_tasks();
        // echo "<pre>";
        // print_r($data['all_leads']);die;

        $this->load->view('leads/task_list', $data);
    }



    public function index() {
        // Just load the page. Events will load via AJAX.
        $this->load->view('calender/calendar_view');
    }

    // FullCalendar can load events via JSON feed
    public function events() {
        $year = (int)$this->input->get('year');
        if ($year < 2000 || $year > 2100) {
            $year = (int)date('Y');
        }

        // 1) Ensure AU holidays exist in DB for this year (cached)
        $this->Calendar_model->sync_au_holidays_from_nager($year);

        // 2) Return all active events for the year
        $events = $this->Calendar_model->get_events_for_year($year);

        // Return JSON
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($events));
    }
}
