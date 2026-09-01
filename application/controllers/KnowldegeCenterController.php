<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KnowldegeCenterController extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('KnowledgeCenterModel');
        $this->load->model('AdminConsoleModel');
    }

    public function viewCourse($id)
    {
        // Load the course data based on the $course_id
        $course = $this->KnowledgeCenterModel->get_course_by_id($id);

        // Pass the course data to your view for editing
        // echo "<pre>";
        // print_r($course);die;
        $data['course_data'] = $course;
        $this->load->view('knowlege_center/view_training_course', $data);
    }

    public function addTraingCourseLesson()
    {
        $data['all_course'] = $this->KnowledgeCenterModel->get_all_training_courses();
        $this->load->view('knowlege_center/add_training_course_lesson',$data);
    }

    public function getAllTrainingCoursesLessons()
    {
       
        // $permissions = [14, 15];
        // $middleware = new PermissionMiddleware();
        // $middleware->handle($permissions);
        $data['all_course_lesson'] = $this->KnowledgeCenterModel->get_all_training_course_lessons();
        // echo "<pre>";
        // print_r($data['all_course_lesson']);die;

        $this->load->view('knowlege_center/training_course_lesson_list', $data);
    }



    public function getAllTrainingCourses()
    {
        // $permissions = [12,13];
        // $middleware = new PermissionMiddleware();
        // $middleware->handle($permissions);
        $data['all_course'] = $this->KnowledgeCenterModel->get_all_training_courses();
        // echo "<pre>";
        // print_r($data['all_course']);die;

        $this->load->view('knowlege_center/training_course_list', $data);
    }



    public function addTraingCourse()
    {
        $this->load->view('knowlege_center/add_training_course');
    }

    public function createCourse()
    {
        // Retrieve form data using $this->input->post('field_name');
        $name = $this->input->post('name');
        $objective = $this->input->post('objective');
        $registration_link = $this->input->post('registration_link');

        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $cover_image = "";
        // Check if a file was selected
        if (!empty($_FILES['COVER_IMAGE']['name'])) {
            // File upload configuration
            $config['upload_path'] = $upload_path; // Specify the upload directory
            $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
            $config['max_size'] = 5120; // Specify max file size in KB (5 MB = 5 * 1024 KB)

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('COVER_IMAGE')) {
                // Handle upload failure
                $error = array('error' => $this->upload->display_errors());
                // var_dump( $error);die;
                // You can handle the error as per your application's requirements
            } else {
                // Upload successful, save the file URL to the database
                $data = array('upload_data' => $this->upload->data());
                $cover_image = base_url() . 'uploads/' . $data['upload_data']['file_name'];
                // Save the updated data to the database using your model
                
            }
           
        }
        // Save the data to the database using your model
        $data = array(
            'name' => $name,
            'objective' => $objective,
            'THUMNAIL_IMAGE' => $cover_image,
            'REGISTRARTION_LINK'=>$registration_link
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
        $data['all_course'] = $this->KnowledgeCenterModel->get_all_training_courses();
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
        $registration_link = $this->input->post('registration_link');

        $upload_path = './uploads/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0777, true);
        }

        $cover_image = "";
        // Check if a file was selected
        if (!empty($_FILES['COVER_IMAGE']['name'])) {
            // File upload configuration
            $config['upload_path'] = $upload_path; // Specify the upload directory
            $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
            $config['max_size'] = 5120; // Specify max file size in KB (5 MB = 5 * 1024 KB)

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('COVER_IMAGE')) {
                // Handle upload failure
                $error = array('error' => $this->upload->display_errors());
                // var_dump( $error);die;
                // You can handle the error as per your application's requirements
            } else {
                // Upload successful, save the file URL to the database
                $data = array('upload_data' => $this->upload->data());
                $cover_image = base_url() . 'uploads/' . $data['upload_data']['file_name'];
                // Save the updated data to the database using your model
                
            }
           
        }
        if($cover_image !=""){
            $data = array(
                'NAME' => $name,
                'OBJECTIVE' => $objective,
                'THUMNAIL_IMAGE' => $cover_image,
                'REGISTRARTION_LINK'=>$registration_link
            );
        }else{
            $data = array(
                'NAME' => $name,
                'OBJECTIVE' => $objective,
                'REGISTRARTION_LINK'=>$registration_link
               
            );
        }
       

        $this->KnowledgeCenterModel->update_course($course_id, $data);
        

        redirect('knowledge-center');
    }


    public function deleteCourse($course_id)
    {

        // Retrieve form data using $this->input->post('field_name');

        $this->KnowledgeCenterModel->delete_course($course_id);

        redirect('knowledge-center');
    }

    // public function getAllTasks()
    // {

    //     $data['all_task'] = $this->LeadModel->get_all_tasks();
    //     // echo "<pre>";
    //     // print_r($data['all_leads']);die;

    //     $this->load->view('leads/task_list', $data);
    // }


    public function createCourseLesson()
{
    // Retrieve form inputs
    $title = $this->input->post('TITLE');
    $lessonForCourse = $this->input->post('COURSE_ID');
    $content = $this->input->post('CONTENT');
    $objective = $this->input->post('OBJECTIVE');
    $lessonVideoLink = $this->input->post('LESSON_VIDEO_LINK');

    // Upload directory
    $uploadPath = './uploads/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $thumbnailImage = '';
    $attachments = []; // Array to store multiple attachment file names

    // Upload THUMNAIL_IMAGE
    if (!empty($_FILES['THUMNAIL_IMAGE']['name'])) {
        $config = [
            'upload_path'   => $uploadPath,
            'allowed_types' => 'gif|jpg|png|jpeg|xlsx',
            'max_size'      => 5120, // 5 MB
        ];

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('THUMNAIL_IMAGE')) {
            $uploadData = $this->upload->data();
            $thumbnailImage = base_url('uploads/' . $uploadData['file_name']);
        } else {
            echo "Thumbnail Image Upload Error: " . $this->upload->display_errors();
            die();
        }
    }

    // Upload Multiple ATTACHMENTS
    if (!empty($_FILES['ATTACHMENT']['name'][0])) {
        $files = $_FILES['ATTACHMENT'];

        for ($i = 0; $i < count($files['name']); $i++) {
            $_FILES['file']['name'] = $files['name'][$i];
            $_FILES['file']['type'] = $files['type'][$i];
            $_FILES['file']['tmp_name'] = $files['tmp_name'][$i];
            $_FILES['file']['error'] = $files['error'][$i];
            $_FILES['file']['size'] = $files['size'][$i];

            $config['allowed_types'] = 'pdf|doc|docx';
            $config['file_name'] = time() . '_' . $files['name'][$i];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('file')) {
                $uploadData = $this->upload->data();
                $attachments[] = base_url('uploads/' . $uploadData['file_name']);
            }
        }
    }

    // Prepare data for database insertion
    $data = [
        'title'            => $title,
        'COURSE_ID'        => $lessonForCourse,
        'content'          => $content,
        'objective'        => $objective,
        'THUMBNAIL_IMAGE'  => $thumbnailImage,
        'ATTACHMENT'       => json_encode($attachments), // Store as JSON array
        'LESSON_VIDEO_LINK'=> $lessonVideoLink,
    ];

    // Save to database
    $this->KnowledgeCenterModel->create_course_lesson($data);

    // Redirect
    redirect('training-course-lesson');
}



    public function viewCourseLesson($id)
    {
        // Load the course data based on the $course_id
        $course = $this->KnowledgeCenterModel->get_course_lesson_by_id($id);

        // Pass the course data to your view for editing
        // echo "<pre>";
        // print_r($course);die;
        $data['lesson_data'] = $course;
        $this->load->view('knowlege_center/view_course_lesson', $data);
    }
    public function editCourseLesson($lesson_id)
    {
        // Load the course data based on the $lesson_id
        $course = $this->KnowledgeCenterModel->get_course_lesson_by_id($lesson_id);
        $data['all_course'] = $this->KnowledgeCenterModel->get_all_training_courses();
        $data['lesson'] = $course;

        // Pass the course data to your view for editing
        // echo "<pre>";
        // print_r($data);die;
        $this->load->view('knowlege_center/edit_course_lesson', $data);
    }

    public function updateCourseLesson()
{
    // Retrieve form inputs
    $lessonId = $this->input->post('LESSON_ID');
    $title = $this->input->post('TITLE');
    $lessonForCourse = $this->input->post('LESSON_FOR_COURSE');
    $content = $this->input->post('CONTENT');
    $objective = $this->input->post('OBJECTIVE');
    $lessonVideoLink = $this->input->post('LESSON_VIDEO_LINK');

    // Upload directory
    $uploadPath = './uploads/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    // Fetch the current values from the database
    $currentLesson = $this->KnowledgeCenterModel->get_lesson_by_id($lessonId);

    // Retain existing files unless replaced
    $thumbnailImage = $currentLesson['THUMBNAIL_IMAGE'];
    $existingAttachments = json_decode($currentLesson['ATTACHMENT'], true) ?? [];

    // Upload new THUMBNAIL_IMAGE if provided
    if (!empty($_FILES['THUMNAIL_IMAGE']['name'])) {
        $config = [
            'upload_path'   => $uploadPath,
            'allowed_types' => 'gif|jpg|png|jpeg|xlsx',
            'max_size'      => 5120, // 5 MB
        ];

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('THUMNAIL_IMAGE')) {
            $uploadData = $this->upload->data();
            $thumbnailImage = base_url('uploads/' . $uploadData['file_name']);
        } else {
            echo "Thumbnail Image Upload Error: " . $this->upload->display_errors();
            die();
        }
    }

    // Upload multiple ATTACHMENTS
    if (!empty($_FILES['ATTACHMENT']['name'][0])) {
        $config = [
            'upload_path'   => $uploadPath,
            'allowed_types' => 'pdf|doc|docx|xlsx',
            'max_size'      => 5120, // 5 MB
        ];

        $this->load->library('upload', $config);

        foreach ($_FILES['ATTACHMENT']['name'] as $key => $value) {
            $_FILES['file']['name'] = $_FILES['ATTACHMENT']['name'][$key];
            $_FILES['file']['type'] = $_FILES['ATTACHMENT']['type'][$key];
            $_FILES['file']['tmp_name'] = $_FILES['ATTACHMENT']['tmp_name'][$key];
            $_FILES['file']['error'] = $_FILES['ATTACHMENT']['error'][$key];
            $_FILES['file']['size'] = $_FILES['ATTACHMENT']['size'][$key];

            $this->upload->initialize($config);

            if ($this->upload->do_upload('file')) {
                $uploadData = $this->upload->data();
                $existingAttachments[] = base_url('uploads/' . $uploadData['file_name']);
            } else {
                echo "Attachment Upload Error: " . $this->upload->display_errors();
                die();
            }
        }
    }

    // Prepare data for database update
    $data = [
        'title'            => $title,
        'COURSE_ID'        => $lessonForCourse,
        'content'          => $content,
        'objective'        => $objective,
        'THUMBNAIL_IMAGE'  => $thumbnailImage,
        'ATTACHMENT'       => json_encode($existingAttachments),
        'LESSON_VIDEO_LINK'=> $lessonVideoLink,
    ];

    // Update the lesson data in the database
    $this->KnowledgeCenterModel->update_course_lesson($lessonId, $data);

    // Redirect to the course lesson list
    redirect('training-course-lesson');
}

    




    public function deleteCourseLesson($lesson_id)
    {

        // Retrieve form data using $this->input->post('field_name');

        $this->KnowledgeCenterModel->delete_course_lesson($lesson_id);

        redirect('training-course-lesson');
    }


    public function lessonAllocation()
    {

        $data['users'] = $this->AdminConsoleModel->get_all_users();
        // echo "<pre>";
        // print_r(  $data['users']);die;

        $this->load->view('knowlege_center/lesson_user_allocation', $data);
    }

    public function getAllCoursesAllocation()
    {
        $user_id = $this->uri->segment(2);
        $data['lessons'] = $this->KnowledgeCenterModel->get_userassigned_courses($user_id);
        // $data['assigned_tests'] = $this->KnowledgeCenterModel->get_tests();

        // echo "pre";
        // print_r(  $data['tests'] );die;

        $this->load->view('knowlege_center/lesson_allocation_user_listing', $data);
    }


    public function addLessonUser()
    {

        $course_id = $this->input->post('course_id');
        $user_id = $this->input->post('user_id');

        // Save the data to the database using your model
        $data = array(
            'COURSE_ID' => $course_id,
            'USER_ID' => $user_id,
        );

        //  print_r($data);die;
        $add_test = $this->KnowledgeCenterModel->save_course_user_mapping($data);

        if ($add_test) {
            echo "Assigned lesson to user";
        }

        // redirect('test');
    }

    public function removeLessonUser()
    {

        $id = $this->input->post('id');
        $user_id = $this->input->post('user_id');
        $is_removed = $this->input->post('is_removed');

        // Save the data to the database using your model
        $data = array(
            'ID' => $id,
            'USER_ID' => $user_id,
        );

        //  print_r($data);die;
        $remove_lesson = $this->KnowledgeCenterModel->remove_user_course_mapping($id, $is_removed);

        if ($remove_lesson) {
            echo "Removed lesson to user";
        }

        // redirect('test');
    }


    public function myLessons() {		

        $course_id = $this->uri->segment(2);
        $data['all_course_lesson'] = $this->KnowledgeCenterModel->get_lessons_of_course($course_id);
        // echo "<pre>";
        // print_r($data['all_course_lesson']);die;

        $this->load->view('knowlege_center/my_training_course_lesson_list', $data);
    }

    public function viewMyCourseLesson($id)
    {
        // Load the course data based on the $course_id
        $course = $this->KnowledgeCenterModel->get_course_lesson_by_id($id);

        // Pass the course data to your view for editing
        // echo "<pre>";
        // print_r($course);die;
        $data['lesson_data'] = $course;
        $this->load->view('knowlege_center/view_my_course_lesson', $data);
    }

    

}
