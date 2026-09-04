<?php
defined('BASEPATH') or exit('No direct script access allowed');

class LandingPageController extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('AdminConsoleModel');
        $this->load->model('KnowledgeCenterModel');
        $this->load->model('LandingPage_Model');
        $this->load->model('Template_Model');
        $this->load->model('User_Model');
        $this->load->library('email');
    }

    public function landingPageDashboard()
    {
        $data['recently_leads'] = $this->LeadModel->get_recent_leads();
        $data['recently_task'] = $this->LeadModel->get_recent_tasks();
        // echo "<pre>";
        // print_r($data);die;
        $this->load->view('leads/lead_dashboard', $data);
    }

    public function getAllLandingPages()
    {


        $data['all_landing_pages'] = $this->LandingPage_Model->get_all_landingpages();
        $this->LandingPage_Model->update();

        // echo "<pre>";
        // print_r($data);
        // die;


        $this->load->view('landingpages/landing_page_list', $data);
    }

    public function addLandingPage($type = 2)
    {
        $data['all_templates'] = $this->Template_Model->get_all_templates();
    
        if ((int)$type === 2) {
            $this->load->view('landingpages/add_landing_page_paymant', $data);
        } else {
            $this->load->view('landingpages/add_landing_page', $data);
        }
    }
    

    public function createNewLandingPage()
{
    // ---- 0) Ensure uploads dir exists ----
    $upload_path = FCPATH . 'uploads/'; // CI-safe absolute path
    if (!is_dir($upload_path)) {
        @mkdir($upload_path, 0777, true);
    }

    // Helper: upload one file field → public URL or ''
    $uploadImage = function($fieldName) use ($upload_path) {
        if (empty($_FILES[$fieldName]['name'])) return '';
        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'gif|jpg|png|jpeg|webp',
            'max_size'      => 5120, // KB = 5MB
            'encrypt_name'  => true,
        ];
        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($fieldName)) {
            // log_message('error', 'Upload error ('.$fieldName.'): '.$this->upload->display_errors('', ''));
            return '';
        }
        $data = $this->upload->data();
        return base_url('uploads/' . $data['file_name']);
    };

    // Defaults (schema has these columns)
    $banner_image_height = 500;
    $banner_image_width  = 1200;

    if (!$this->input->post()) {
        return redirect('landingpages/add'); // adjust route if needed
    }

    // ---- 1) Type ----
    $type = (int) $this->input->post('LANDING_PAGE_TYPE');
    if ($type !== 1 && $type !== 2) { $type = 1; }

    // ---- 2) Uploads ----
    $banner_image = $uploadImage('BANNER_IMAGE');                          // common
    $share_image  = ($type === 2) ? $uploadImage('SHARE_IMAGE') : '';      // type2 only

    // Coach photo precedence: file > URL text (type2 only)
    $coach_photo = '';
    if ($type === 2) {
        $coach_photo_file = $uploadImage('COACH_PHOTO_FILE');
        $coach_photo_text = trim((string) $this->input->post('COACH_PHOTO'));
        $coach_photo      = $coach_photo_file ?: $coach_photo_text;
    }

    // ---- 3) Slug (URL) ----
    $rawSlug = trim((string) $this->input->post('LANDING_PAGE_URL'));
    if ($rawSlug === '') {
        $nameForSlug = trim((string) $this->input->post('LANDING_PAGE_NAME'));
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $nameForSlug), '-'));
    } else {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $rawSlug), '-'));
    }

    // ---- 4) Base data (present in your schema) ----
    $data = [
        'LANDING_PAGE_TYPE'           => $type,
        'LANDING_PAGE_NAME'           => trim((string) $this->input->post('LANDING_PAGE_NAME')) ?: null,
        'LANDING_PAGE_URL'            => $slug ?: null,
        'BANNER_IMAGE'                => $banner_image ?: null,
        'BANNER_IMAGE_HEIGHT'         => $banner_image_height,
        'BANNER_IMAGE_WIDTH'          => $banner_image_width,
        'CC_EMAIL'                    => trim((string) $this->input->post('CC_EMAIL')) ?: null,
        'REGISTERED_TEMPLATE'         => (string) $this->input->post('REGISTERED_TEMPLATE') !== '' ? (string) $this->input->post('REGISTERED_TEMPLATE') : null, // varchar in schema
        'PAYMENT_TEMPLATE'            => ($this->input->post('PAMENT_TEMPLATE') !== null) ? (int)$this->input->post('PAMENT_TEMPLATE') : null, // FIXED name
        'EMAIL_TEMPLATE'              => ($this->input->post('EMAIL_TEMPLATE') !== null) ? (int)$this->input->post('EMAIL_TEMPLATE') : null,
        'LANDING_PAGE_LEFT_CONTENT'   => (string) $this->input->post('LANDING_PAGE_LEFT_CONTENT') ?: null,
        'LANDING_PAGE_MAIN_CONTENT'   => (string) $this->input->post('LANDING_PAGE_MAIN_CONTENT') ?: null,
        'LANDING_PAGE_RIGHT_CONTENT'  => (string) $this->input->post('LANDING_PAGE_RIGHT_CONTENT') ?: null,
        'LANDING_PAGE_THANKS_CONTENT' => (string) $this->input->post('LANDING_PAGE_THANKS_CONTENT') ?: null,
        // legacy fees are VARCHAR in schema; keep as empty string or NULL (using NULL here)
        'LANDING_PAGE_FEE'            => ($this->input->post('LANDING_PAGE_FEE') === '') ? null : (string)$this->input->post('LANDING_PAGE_FEE'),
        'LANDING_PAGE_VIP_FEE'        => ($this->input->post('LANDING_PAGE_VIP_FEE') === '') ? null : (string)$this->input->post('LANDING_PAGE_VIP_FEE'),
    ];

    // ---- 5) Type 1 legacy VIP fields ----
    if ($type === 1) {
        $data['LANDING_PAGE_VIP_EXPERIENCE_CONTENT']       = (string) $this->input->post('LANDING_PAGE_VIP_EXPERIENCE_CONTENT') ?: null;
        $data['LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS'] = (string) $this->input->post('LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS') ?: null;
    }

    // ---- 6) Type 2 extra fields (these exist in your schema per last DDL) ----
    if ($type === 2) {
        $data['SHARE_IMAGE']        = $share_image ?: null; // schema changed to VARCHAR(500) in prior ALTER
        $data['TESTIMONIALS_IMAGES']= trim((string) $this->input->post('TESTIMONIALS_IMAGES')) ?: null;
        $data['TESTIMONIALS_VIDEOS']= trim((string) $this->input->post('TESTIMONIALS_VIDEOS')) ?: null;

        // Coach / Trainer (added via ALTER script previously)
        $data['COACH_NAME']         = trim((string) $this->input->post('COACH_NAME')) ?: null;
        $data['COACH_SUBTITLE']     = trim((string) $this->input->post('COACH_SUBTITLE')) ?: null;
        $data['COACH_PHOTO']        = $coach_photo ?: null;
        $data['COACH_BIO_SHORT']    = (string) $this->input->post('COACH_BIO_SHORT') ?: null;
        $data['COACH_BIO_LONG']     = (string) $this->input->post('COACH_BIO_LONG') ?: null;

        // Pricing & payments (DECIMAL columns per ALTER)
        $data['CURRENCY']           = ($cur = strtolower(trim((string)$this->input->post('CURRENCY')))) ? $cur : 'aud';
        foreach (['PRICE_FIRST_TRAINING','PRICE_SECOND_TRAINING','PRICE_BUNDLE_TOTAL','PRICE_DEPOSIT'] as $k) {
            $v = $this->input->post($k);
            $data[$k] = ($v === '' || $v === null) ? null : (float)$v;
        }
     
        // Help / Connect
        $data['WHATSAPP_NUMBER']    = trim((string)$this->input->post('WHATSAPP_NUMBER')) ?: null;
        $data['WHATSAPP_TEXT']      = trim((string)$this->input->post('WHATSAPP_TEXT')) ?: null;
        $data['MESSENGER_PAGE_URL'] = trim((string)$this->input->post('MESSENGER_PAGE_URL')) ?: null;
        $data['MESSENGER_TRAINER_URL'] = trim((string)$this->input->post('MESSENGER_TRAINER_URL')) ?: null;

        // Optional copy tweak
        $data['VIP_CARD_TEXT']      = trim((string)$this->input->post('VIP_CARD_TEXT')) ?: null;

        // FAQs → JSON
        $faqQ = $this->input->post('FAQ_QUESTIONS');
        $faqA = $this->input->post('FAQ_ANSWERS');
        $faq  = [];
        if (is_array($faqQ) && is_array($faqA)) {
            $len = max(count($faqQ), count($faqA));
            for ($i = 0; $i < $len; $i++) {
                $q = isset($faqQ[$i]) ? trim((string)$faqQ[$i]) : '';
                $a = isset($faqA[$i]) ? trim((string)$faqA[$i]) : '';
                if ($q === '' && $a === '') continue;
                $faq[] = ['q' => $q, 'a' => $a];
            }
        }
        $data['FAQ_JSON'] = !empty($faq) ? json_encode($faq, JSON_UNESCAPED_UNICODE) : null;
    }

    // ---- 7) Allow-list to avoid “unknown column” DB errors if schema lags ----
    // Matches your schema after the ALTER script I provided.
    $allowed = [
        'LANDING_PAGE_TYPE','LANDING_PAGE_NAME','LANDING_PAGE_URL',
        'BANNER_IMAGE','BANNER_IMAGE_HEIGHT','BANNER_IMAGE_WIDTH',
        'CC_EMAIL','REGISTERED_TEMPLATE','PAYMENT_TEMPLATE','EMAIL_TEMPLATE','LANDING_PAGE_MAIN_CONTENT',
        'LANDING_PAGE_LEFT_CONTENT','LANDING_PAGE_RIGHT_CONTENT','LANDING_PAGE_THANKS_CONTENT',
        'LANDING_PAGE_FEE','LANDING_PAGE_VIP_FEE',
        'LANDING_PAGE_VIP_EXPERIENCE_CONTENT','LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS',
        'SHARE_IMAGE','TESTIMONIALS_IMAGES','TESTIMONIALS_VIDEOS',
        'COACH_NAME','COACH_SUBTITLE','COACH_PHOTO','COACH_BIO_SHORT','COACH_BIO_LONG',
        'CURRENCY','PRICE_FIRST_TRAINING','PRICE_SECOND_TRAINING','PRICE_BUNDLE_TOTAL','PRICE_DEPOSIT',
        'WHATSAPP_NUMBER','WHATSAPP_TEXT',
        'MESSENGER_PAGE_URL','MESSENGER_TRAINER_URL','VIP_CARD_TEXT','FAQ_JSON'
    ];
    $data = array_intersect_key($data, array_flip($allowed));

    // echo "<pre>";
    // print_r($data);
    // die;
    // ---- 8) Persist & redirect ----
    $this->LandingPage_Model->insertLandingPage($data);
    return redirect('landing-pages');
}

    


    public function editLandingPage($id)
    {

        $data['all_templates'] = $this->Template_Model->get_all_templates();
        $data['all_form_templates'] = $this->Template_Model->get_all_templates();
        $data['landing_page'] = $this->LandingPage_Model->getLandingPageById($id);

        // echo "<pre>";
        // print_r($data['landing_page']['LANDING_PAGE_TYPE']);die;


      
        if($data['landing_page']['LANDING_PAGE_TYPE'] == 2)
        {
            // echo "payment";die;
            $this->load->view('landingpages/edit_landing_page_payment', $data);
        }
        else
        {
            // echo "basic";die;
            $this->load->view('landingpages/edit_landing_page', $data);
        }
        // $this->load->view('landingpages/edit_landing_page', $data);
    }



    // public function updateLandingPage()
    // {
    //     $id = $this->input->post('id');
    //     $old_data = $this->LandingPage_Model->getLandingPageById($id);
    //     // Create the uploads directory if it doesn't exist
    //     $upload_path = './uploads/';
    //     if (!is_dir($upload_path)) {
    //         mkdir($upload_path, 0777, true);
    //     }

    //     $banner_image = $old_data['BANNER_IMAGE']; // Get the existing image URL
    //     $testimonial_images_urls = $old_data['TESTIMONIALS_IMAGES'];
    //     $banner_image_height = $this->input->post('BANNER_IMAGE_HEIGHT'); // Get the existing image URL
    //     $banner_image_width = $this->input->post('BANNER_IMAGE_WIDTH'); // Get the existing image URL
    //     // Check if a file was selected
    //     if (!empty($_FILES['BANNER_IMAGE']['name'])) {
    //         // File upload configuration
    //         $config['upload_path'] = $upload_path; // Specify the upload directory
    //         $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Specify allowed file types
    //         $config['max_size'] = 5120; // Specify max file size in KB (5 MB = 5 * 1024 KB)

    //         $this->load->library('upload', $config);

    //         if (!$this->upload->do_upload('BANNER_IMAGE')) {
    //             // Handle upload failure
    //             $error = array('error' => $this->upload->display_errors());
    //             // var_dump( $error);die;
    //             // You can handle the error as per your application's requirements
    //         } else {
    //             // Upload successful, save the file URL to the database
    //             $data = array('upload_data' => $this->upload->data());
    //             $banner_image = base_url() . 'uploads/' . $data['upload_data']['file_name'];
    //         }
    //     }

        
    
    //     // Handle TESTIMONIALS_IMAGES upload
    //     if (!empty($_FILES['TESTIMONIALS_IMAGES']['name'][0])) { // Check if any file is uploaded
    //         foreach ($_FILES['TESTIMONIALS_IMAGES']['tmp_name'] as $key => $tmp_name) {
    //             $file_name = $_FILES['TESTIMONIALS_IMAGES']['name'][$key];
    //             $file_tmp = $_FILES['TESTIMONIALS_IMAGES']['tmp_name'][$key];
                
    //             // Define upload directory
    //             $upload_dir = "uploads/";
                
    //             // Ensure the directory exists
    //             if (!is_dir($upload_dir)) {
    //                 mkdir($upload_dir, 0777, true);
    //             }
                
    //             // Generate a unique file name to avoid conflicts
    //             $unique_name = uniqid() . '_' . $file_name;
                
    //             // Move the uploaded file to the directory
    //             if (move_uploaded_file($file_tmp, $upload_dir . $unique_name)) {
    //                 // Add the file URL to the array
    //                 $uploaded_images[] = $upload_dir . $unique_name;
    //             }
    //         }
    //         $testimonial_images_urls = implode(',', $uploaded_images);
    //     }
        
    //     // Convert image URLs to a comma-separated string
     

    //     // echo  $testimonial_images_urls;die;

    //     $id = $this->input->post('id');
    //     $data = array(
    //         'LANDING_PAGE_NAME' => $this->input->post('LANDING_PAGE_NAME'),
    //         'LANDING_PAGE_URL' => $this->input->post('LANDING_PAGE_URL'),
    //         'BANNER_IMAGE' => $banner_image,
    //         'TESTIMONIALS_IMAGES' => $testimonial_images_urls,
    //         'BANNER_IMAGE_HEIGHT' => $banner_image_height,
    //         'BANNER_IMAGE_WIDTH' => $banner_image_width,
    //         'CC_EMAIL' => $this->input->post('CC_EMAIL'),
    //         'REGISTERED_TEMPLATE' => $this->input->post('REGISTERED_TEMPLATE'),
    //         'PAMENT_TEMPLATE' => $this->input->post('PAMENT_TEMPLATE'),
    //         'EMAIL_TEMPLATE' => $this->input->post('EMAIL_TEMPLATE'),
    //         'LANDING_PAGE_LEFT_CONTENT' => $this->input->post('LANDING_PAGE_LEFT_CONTENT'),
    //         'LANDING_PAGE_RIGHT_CONTENT' => $this->input->post('LANDING_PAGE_RIGHT_CONTENT'),
    //         'LANDING_PAGE_VIP_EXPERIENCE_CONTENT' => $this->input->post('LANDING_PAGE_VIP_EXPERIENCE_CONTENT'),
    //         'LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS' => $this->input->post('LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS'),
    //         'LANDING_PAGE_THANKS_CONTENT' => $this->input->post('LANDING_PAGE_THANKS_CONTENT'),
    //         'LANDING_PAGE_FEE' => $this->input->post('LANDING_PAGE_FEE'),
    //         'LANDING_PAGE_VIP_FEE' => $this->input->post('LANDING_PAGE_VIP_FEE')
    //     );

    //     $this->LandingPage_Model->updateLandingPage($id, $data);

    //     // Redirect to a success page or show a success message
    //     redirect('landing-pages');
    // }

    public function updateLandingPage()
{
    if (!$this->input->post('id')) {
        return redirect('landing-pages');
    }

    $id        = (int) $this->input->post('id');
    $old       = $this->LandingPage_Model->getLandingPageById($id);
    if (!$old) { return redirect('landing-pages'); }

    // ---- 0) Ensure uploads dir exists ----
    $upload_path = FCPATH . 'uploads/'; // absolute, CI-safe
    if (!is_dir($upload_path)) { @mkdir($upload_path, 0777, true); }

    // Helper: upload one file field → public URL or ''
    $uploadImage = function($fieldName) use ($upload_path) {
        if (empty($_FILES[$fieldName]['name'])) return '';
        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'gif|jpg|png|jpeg|webp',
            'max_size'      => 5120, // KB = 5MB
            'encrypt_name'  => true,
        ];
        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($fieldName)) {
            // log_message('error', 'Upload error ('.$fieldName.'): '.$this->upload->display_errors('', ''));
            return '';
        }
        $data = $this->upload->data();
        return base_url('uploads/' . $data['file_name']);
    };

    // ---- 1) Type ----
    $type = (int) $this->input->post('LANDING_PAGE_TYPE');
    if ($type !== 1 && $type !== 2) {
        // keep old if any, else default 1
        $type = isset($old['LANDING_PAGE_TYPE']) ? (int)$old['LANDING_PAGE_TYPE'] : 1;
    }

    // ---- 2) Uploads ----
    // If no new file uploaded, keep old
    $new_banner  = $uploadImage('BANNER_IMAGE');
    $banner_image = $new_banner ?: (isset($old['BANNER_IMAGE']) ? $old['BANNER_IMAGE'] : null);

    $share_image = null;
    if ($type === 2) {
        $new_share = $uploadImage('SHARE_IMAGE');
        $share_image = $new_share ?: (isset($old['SHARE_IMAGE']) ? $old['SHARE_IMAGE'] : null);
    }

    // Coach photo precedence: file > text > old
    $coach_photo = null;
    if ($type === 2) {
        $coach_photo_file = $uploadImage('COACH_PHOTO_FILE');
        $coach_photo_text = trim((string)$this->input->post('COACH_PHOTO'));
        $coach_photo      = $coach_photo_file ?: ($coach_photo_text ?: (isset($old['COACH_PHOTO']) ? $old['COACH_PHOTO'] : null));
    }

    // ---- 3) Slug (URL) ----
    $rawSlug = trim((string)$this->input->post('LANDING_PAGE_URL'));
    if ($rawSlug === '') {
        // If not provided, keep old slug if available; else make from (possibly updated) name
        $slug = isset($old['LANDING_PAGE_URL']) && $old['LANDING_PAGE_URL'] !== ''
              ? $old['LANDING_PAGE_URL']
              : strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', (string)$this->input->post('LANDING_PAGE_NAME')), '-'));
    } else {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', $rawSlug), '-'));
    }

    // ---- 4) Dimensions (defaults if missing) ----
    $banner_image_height = $this->input->post('BANNER_IMAGE_HEIGHT');
    $banner_image_width  = $this->input->post('BANNER_IMAGE_WIDTH');

    $banner_image_height = ($banner_image_height === null || $banner_image_height === '')
        ? (isset($old['BANNER_IMAGE_HEIGHT']) ? (int)$old['BANNER_IMAGE_HEIGHT'] : 500)
        : (int)$banner_image_height;

    $banner_image_width = ($banner_image_width === null || $banner_image_width === '')
        ? (isset($old['BANNER_IMAGE_WIDTH']) ? (int)$old['BANNER_IMAGE_WIDTH'] : 1200)
        : (int)$banner_image_width;

    // ---- 5) Base data (same schema as create) ----
    $data = [
        'LANDING_PAGE_TYPE'           => $type,
        'LANDING_PAGE_NAME'           => trim((string)$this->input->post('LANDING_PAGE_NAME')) ?: (isset($old['LANDING_PAGE_NAME']) ? $old['LANDING_PAGE_NAME'] : null),
        'LANDING_PAGE_URL'            => $slug ?: (isset($old['LANDING_PAGE_URL']) ? $old['LANDING_PAGE_URL'] : null),
        'BANNER_IMAGE'                => $banner_image ?: null,
        'BANNER_IMAGE_HEIGHT'         => $banner_image_height,
        'BANNER_IMAGE_WIDTH'          => $banner_image_width,
        'CC_EMAIL'                    => trim((string)$this->input->post('CC_EMAIL')) ?: (isset($old['CC_EMAIL']) ? $old['CC_EMAIL'] : null),

        // REGISTERED_TEMPLATE left as varchar in your schema
        'REGISTERED_TEMPLATE'         => ($this->input->post('REGISTERED_TEMPLATE') !== null && $this->input->post('REGISTERED_TEMPLATE') !== '')
                                          ? (string)$this->input->post('REGISTERED_TEMPLATE')
                                          : (isset($old['REGISTERED_TEMPLATE']) ? $old['REGISTERED_TEMPLATE'] : null),

        // FIXED: PAYMENT_TEMPLATE (not PAMENT_TEMPLATE)
        'PAYMENT_TEMPLATE'            => ($this->input->post('PAMENT_TEMPLATE') !== null && $this->input->post('PAMENT_TEMPLATE') !== '')
                                          ? (int)$this->input->post('PAMENT_TEMPLATE')
                                          : (isset($old['PAYMENT_TEMPLATE']) ? (int)$old['PAYMENT_TEMPLATE'] : null),

        'EMAIL_TEMPLATE'              => ($this->input->post('EMAIL_TEMPLATE') !== null && $this->input->post('EMAIL_TEMPLATE') !== '')
                                          ? (int)$this->input->post('EMAIL_TEMPLATE')
                                          : (isset($old['EMAIL_TEMPLATE']) ? (int)$old['EMAIL_TEMPLATE'] : null),

        'LANDING_PAGE_LEFT_CONTENT'   => (string)$this->input->post('LANDING_PAGE_LEFT_CONTENT')  ?: (isset($old['LANDING_PAGE_LEFT_CONTENT']) ? $old['LANDING_PAGE_LEFT_CONTENT'] : null),
        'LANDING_PAGE_MAIN_CONTENT'   => (string)$this->input->post('LANDING_PAGE_MAIN_CONTENT')  ?: (isset($old['LANDING_PAGE_MAIN_CONTENT']) ? $old['LANDING_PAGE_MAIN_CONTENT'] : null),
        'LANDING_PAGE_RIGHT_CONTENT'  => (string)$this->input->post('LANDING_PAGE_RIGHT_CONTENT') ?: (isset($old['LANDING_PAGE_RIGHT_CONTENT']) ? $old['LANDING_PAGE_RIGHT_CONTENT'] : null),
        'LANDING_PAGE_THANKS_CONTENT' => (string)$this->input->post('LANDING_PAGE_THANKS_CONTENT') ?: (isset($old['LANDING_PAGE_THANKS_CONTENT']) ? $old['LANDING_PAGE_THANKS_CONTENT'] : null),

        // legacy fees as strings
        'LANDING_PAGE_FEE'            => ($this->input->post('LANDING_PAGE_FEE') === null || $this->input->post('LANDING_PAGE_FEE') === '')
                                          ? (isset($old['LANDING_PAGE_FEE']) ? $old['LANDING_PAGE_FEE'] : null)
                                          : (string)$this->input->post('LANDING_PAGE_FEE'),

        'LANDING_PAGE_VIP_FEE'        => ($this->input->post('LANDING_PAGE_VIP_FEE') === null || $this->input->post('LANDING_PAGE_VIP_FEE') === '')
                                          ? (isset($old['LANDING_PAGE_VIP_FEE']) ? $old['LANDING_PAGE_VIP_FEE'] : null)
                                          : (string)$this->input->post('LANDING_PAGE_VIP_FEE'),
    ];

    // ---- 6) Type 1 legacy VIP fields ----
    if ($type === 1) {
        $data['LANDING_PAGE_VIP_EXPERIENCE_CONTENT']       =
            (string)$this->input->post('LANDING_PAGE_VIP_EXPERIENCE_CONTENT') ?: (isset($old['LANDING_PAGE_VIP_EXPERIENCE_CONTENT']) ? $old['LANDING_PAGE_VIP_EXPERIENCE_CONTENT'] : null);

        $data['LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS'] =
            (string)$this->input->post('LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS') ?: (isset($old['LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS']) ? $old['LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS'] : null);
    }

    // ---- 7) Type 2 extra fields ----
    if ($type === 2) {
        $data['SHARE_IMAGE'] = $share_image ?: null;

        // For update: prefer posted text fields (to match create), but keep old if empty
        $data['TESTIMONIALS_IMAGES'] =
            trim((string)$this->input->post('TESTIMONIALS_IMAGES')) !== ''
                ? trim((string)$this->input->post('TESTIMONIALS_IMAGES'))
                : (isset($old['TESTIMONIALS_IMAGES']) ? $old['TESTIMONIALS_IMAGES'] : null);

        $data['TESTIMONIALS_VIDEOS'] =
            trim((string)$this->input->post('TESTIMONIALS_VIDEOS')) !== ''
                ? trim((string)$this->input->post('TESTIMONIALS_VIDEOS'))
                : (isset($old['TESTIMONIALS_VIDEOS']) ? $old['TESTIMONIALS_VIDEOS'] : null);

        // Coach / Trainer
        $data['COACH_NAME']      = trim((string)$this->input->post('COACH_NAME'))      ?: (isset($old['COACH_NAME']) ? $old['COACH_NAME'] : null);
        $data['COACH_SUBTITLE']  = trim((string)$this->input->post('COACH_SUBTITLE'))  ?: (isset($old['COACH_SUBTITLE']) ? $old['COACH_SUBTITLE'] : null);
        $data['COACH_PHOTO']     = $coach_photo ?: null;
        $data['COACH_BIO_SHORT'] = (string)$this->input->post('COACH_BIO_SHORT') ?: (isset($old['COACH_BIO_SHORT']) ? $old['COACH_BIO_SHORT'] : null);
        $data['COACH_BIO_LONG']  = (string)$this->input->post('COACH_BIO_LONG')  ?: (isset($old['COACH_BIO_LONG'])  ? $old['COACH_BIO_LONG']  : null);

        // Pricing & payments
        $curPost = strtolower(trim((string)$this->input->post('CURRENCY')));
        $data['CURRENCY'] = $curPost ?: (isset($old['CURRENCY']) ? $old['CURRENCY'] : 'aud');

        foreach (['PRICE_FIRST_TRAINING','PRICE_SECOND_TRAINING','PRICE_BUNDLE_TOTAL','PRICE_DEPOSIT'] as $k) {
            $v = $this->input->post($k);
            if ($v === '' || $v === null) {
                $data[$k] = isset($old[$k]) ? (float)$old[$k] : null;
            } else {
                $data[$k] = (float)$v;
            }
        }

        // Help / Connect
        $data['WHATSAPP_NUMBER']        = trim((string)$this->input->post('WHATSAPP_NUMBER'))        ?: (isset($old['WHATSAPP_NUMBER']) ? $old['WHATSAPP_NUMBER'] : null);
        $data['WHATSAPP_TEXT']          = trim((string)$this->input->post('WHATSAPP_TEXT'))          ?: (isset($old['WHATSAPP_TEXT']) ? $old['WHATSAPP_TEXT'] : null);
        $data['MESSENGER_PAGE_URL']     = trim((string)$this->input->post('MESSENGER_PAGE_URL'))     ?: (isset($old['MESSENGER_PAGE_URL']) ? $old['MESSENGER_PAGE_URL'] : null);
        $data['MESSENGER_TRAINER_URL']  = trim((string)$this->input->post('MESSENGER_TRAINER_URL'))  ?: (isset($old['MESSENGER_TRAINER_URL']) ? $old['MESSENGER_TRAINER_URL'] : null);

        // Optional copy
        $data['VIP_CARD_TEXT']          = trim((string)$this->input->post('VIP_CARD_TEXT'))          ?: (isset($old['VIP_CARD_TEXT']) ? $old['VIP_CARD_TEXT'] : null);

        // FAQs → JSON (build from parallel arrays)
        $faqQ = $this->input->post('FAQ_QUESTIONS');
        $faqA = $this->input->post('FAQ_ANSWERS');
        $faq  = [];
        if (is_array($faqQ) && is_array($faqA)) {
            $len = max(count($faqQ), count($faqA));
            for ($i = 0; $i < $len; $i++) {
                $q = isset($faqQ[$i]) ? trim((string)$faqQ[$i]) : '';
                $a = isset($faqA[$i]) ? trim((string)$faqA[$i]) : '';
                if ($q === '' && $a === '') continue;
                $faq[] = ['q' => $q, 'a' => $a];
            }
        }
        // If arrays not posted, keep old FAQ_JSON
        if (!empty($faq)) {
            $data['FAQ_JSON'] = json_encode($faq, JSON_UNESCAPED_UNICODE);
        } else {
            $data['FAQ_JSON'] = isset($old['FAQ_JSON']) ? $old['FAQ_JSON'] : null;
        }
    }

    // ---- 8) Allow-list (same as create) ----
    $allowed = [
        'LANDING_PAGE_TYPE','LANDING_PAGE_NAME','LANDING_PAGE_URL',
        'BANNER_IMAGE','BANNER_IMAGE_HEIGHT','BANNER_IMAGE_WIDTH',
        'CC_EMAIL','REGISTERED_TEMPLATE','PAYMENT_TEMPLATE','EMAIL_TEMPLATE','LANDING_PAGE_MAIN_CONTENT',
        'LANDING_PAGE_LEFT_CONTENT','LANDING_PAGE_RIGHT_CONTENT','LANDING_PAGE_THANKS_CONTENT',
        'LANDING_PAGE_FEE','LANDING_PAGE_VIP_FEE',
        'LANDING_PAGE_VIP_EXPERIENCE_CONTENT','LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS',
        'SHARE_IMAGE','TESTIMONIALS_IMAGES','TESTIMONIALS_VIDEOS',
        'COACH_NAME','COACH_SUBTITLE','COACH_PHOTO','COACH_BIO_SHORT','COACH_BIO_LONG',
        'CURRENCY','PRICE_FIRST_TRAINING','PRICE_SECOND_TRAINING','PRICE_BUNDLE_TOTAL','PRICE_DEPOSIT',
        'WHATSAPP_NUMBER','WHATSAPP_TEXT',
        'MESSENGER_PAGE_URL','MESSENGER_TRAINER_URL','VIP_CARD_TEXT','FAQ_JSON'
    ];
    $data = array_intersect_key($data, array_flip($allowed));

    // echo "<pre>";
    // print_r($data);die;
    // ---- 9) Persist & redirect ----
    $this->LandingPage_Model->updateLandingPage($id, $data);
    return redirect('landing-page-edit/' . $id)
        ->with('success', 'Landing page updated successfully.');
    
}


    public function viewLandingPage($id)
    {
        $data['landing_page'] = $this->LandingPage_Model->getLandingPageById($id);
        // echo "<pre>";
        // print_r($data['landing_page']);
        // die;
        
        $this->load->view('landingpages/view_landing_page', $data);
    }

    public function viewLandingPagePreview($slug, $id) {
        // Fetch landing page data by ID
        $landing_page = $this->LandingPage_Model->getLandingPageById($id);

        // echo "<pre>";
        // print_r($landing_page); 
        // die;
    
        // Check if the landing page exists
        if (!$landing_page) {
            show_404(); // Show a 404 error if the page does not exist
            return;
        }
    
        // Convert actual landing page name to a slug format
        $correct_slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $landing_page['LANDING_PAGE_NAME']);
        $correct_slug = trim($correct_slug, '-'); // Remove any trailing hyphens
    
        // Check if the slug in the URL matches the correct slug
        if ($slug !== $correct_slug) {
            // Redirect to the correct URL
            redirect(base_url($correct_slug . '/' . $id));
            return;
        }
    
        // Load data into the view
        $data['landing_page'] = $landing_page;
        $data['landing_page_videos'] = $this->LandingPage_Model->get_all_videosOfLandingPage($id);
    
        if($id == 23 )
        {
            $this->load->view('landingpages/landing_page_payment', $data);
        }elseif($data['landing_page']['LANDING_PAGE_TYPE'] == 2)
        {
            // echo "<pre>";
            // print_r($data);
            // die;
            $this->load->view('landingpages/landing_page_payment_custom', $data);
        }   
        else
        {
            $this->load->view('landingpages/landing_page', $data);
        }
       
    }
    
    

    public function deleteLandingPage($id)
    {


        // Call the model function to update the is_del column
        $result = $this->LandingPage_Model->deleteLandingPage($id);
        // Send response back to the AJAX call
        redirect('landing-pages');
    }

    public function videoList($id)
    {
        $data['videos_data'] = $this->LandingPage_Model->get_all_videosOfLandingPage($id);

        $data['LANDING_PAGE_ID'] = $id;

        $this->load->view('landingpages/videos_list_page', $data);
    }

    public function addVideo($id)
    {
        if ($this->input->post()) {
            $user_id = $this->session->userdata('user')['USER_ID'];

            $data = array(
                'LANDING_PAGE_ID' => $this->input->post('LANDING_PAGE_ID'),
                'VIDEO_TITLE' => $this->input->post('VIDEO_TITLE'),
                'VIDEO_TEXT' => $this->input->post('VIDEO_TEXT'),
                'CREATED_BY' => $user_id,
                'RECORD_STATUS' =>  1,
                'CREATED_ON' => date('Y-m-d H:i:s'),
            );

            $this->LandingPage_Model->insertVideo($data);

            // Redirect to a success page or show a success message
            $data['videos_data'] = $this->LandingPage_Model->get_all_videosOfLandingPage($id);
            $data['LANDING_PAGE_ID'] = $id;

            $this->load->view('landingpages/videos_list_page', $data);
        } else {
            $data['LANDING_PAGE_ID'] = $id;
            $this->load->view('landingpages/add_video_page', $data);
        }
    }

    public function updateVideo($id)
    {
        if ($this->input->post()) {
            $user_id = $this->session->userdata('user')['USER_ID'];
            $old_data = $this->LandingPage_Model->get_videoData($id);

            $data = array(
                'LANDING_PAGE_ID' => $this->input->post('LANDING_PAGE_ID'),
                'VIDEO_TITLE' => $this->input->post('VIDEO_TITLE'),
                'VIDEO_TEXT' => $this->input->post('VIDEO_TEXT'),
                'MODIFIED_BY' => $user_id,
                'MODIFIED_ON' => date('Y-m-d H:i:s'),
            );

            $this->LandingPage_Model->updateVideo($id, $data);
            $data['videos_data'] = $this->LandingPage_Model->get_videoData($id);

            redirect('videos-list/' . $data['videos_data']['LANDING_PAGE_ID'] . '');
        } else {
            $data['videos_data'] = $this->LandingPage_Model->get_videoData($id);
            $this->load->view('landingpages/edit_video_page', $data);
        }
    }
    public function viewVideo($id)
    {
        $data['videos_data'] = $this->LandingPage_Model->get_videoData($id);

        $this->load->view('landingpages/view_video_page', $data);
    }

    public function deleteVideo($id)
    {
        $data['videos_data'] = $this->LandingPage_Model->get_videoData($id);

        $result = $this->LandingPage_Model->deleteVideo($id);

        // Redirect to a success page or show a success message

        redirect('videos-list/' . $data['videos_data']['LANDING_PAGE_ID'] . '');
    }

    public function submit_contact()
{
    $this->load->helper(['security', 'email']); 
    $this->load->library('email');
    $this->load->model('User_Model'); 
    $this->load->model('LandingPage_Model');
    $this->load->model('Template_Model');

    $name     = $this->input->post('name', TRUE);
    $mobile   = $this->input->post('mobile', TRUE);
    $email    = $this->input->post('email', TRUE);
    $platform = $this->input->post('platform', TRUE);
    $country_code = $this->input->post('country_code', TRUE);
    $gift_id  = $this->input->post('gift_id', TRUE);
    $email_template_id = $this->input->post('EMAIL_TEMPLATE', TRUE);
    $landing_page_name = $this->input->post('LANDING_PAGE_NAME', TRUE);

    if (empty($email_template_id) || $email_template_id == -1) {
        $default_template = $this->Template_Model->get_first_template();
        if ($default_template) {
            $email_template_id = $default_template['TEMPLATE_ID'];
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No email template available.']);
            return;
        }
    }

    $existing_user = $this->User_Model->get_user_by_email_or_mobile($email, $mobile);

    $hashed_password = "Eyd2026"; // Consider hashing in real applications

    $training_dates = 'To be announced';
    $training_time  = 'To be announced';
    $training_link  = 'https://eyd.franhive.com/login';

    // Email sending function with attachment support (existing functionality preserved)
    $send_email = function($subject, $message_body, $attachments = []) use ($email) {
        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'smtp.hostinger.com',
            'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass'   => 'Franh1ve@2024',
            'smtp_port'   => 465,
            'smtp_crypto' => 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
        ];

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from(EMAIL_CONFIG_EMAIL, 'Barinderjeet Kaur');
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($message_body);

        foreach ($attachments as $attachment_path) {
            if (file_exists($attachment_path)) {
                $this->email->attach($attachment_path);
            }
        }

        if (!$this->email->send()) {
            echo json_encode(['status' => 'error', 'message' => 'Email sending failed: ' . $this->email->print_debugger()]);
            return false;
        }
        return true;
    };

    // ✅ NEW: Admin receipt/notification email (does NOT break existing flow if it fails)
    $send_admin_email = function($subject, $message_body) {
        $config = [
            'protocol'    => 'smtp',
            'smtp_host'   => 'smtp.hostinger.com',
            'smtp_user'   => 'nlp@empoweryourdestiny.com.au',
            'smtp_pass'   => 'Franh1ve@2024',
            'smtp_port'   => 465,
            'smtp_crypto' => 'ssl',
            'mailtype'    => 'html',
            'charset'     => 'utf-8',
            'newline'     => "\r\n",
        ];

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");

        $this->email->from(EMAIL_CONFIG_EMAIL, 'Barinderjeet Kaur');
        $this->email->to('nlp@empoweryourdestiny.com.au'); // receipt to admin
        $this->email->subject($subject);
        $this->email->message($message_body);

        // Soft fail: don’t echo/return JSON error, just log
        if (!$this->email->send()) {
            log_message('error', 'Admin receipt email failed: ' . $this->email->print_debugger());
            return false;
        }
        return true;
    };

    if (!$existing_user) {
        $user_data = [
            'NAME'       => $name,
            'MOBILE'     => $mobile,
            'EMAIL'      => $email,
            'PASSWORD'   => $hashed_password,
            'CREATED_ON' => date('Y-m-d H:i:s')
        ];

        $user_id = $this->User_Model->create_user($user_data);

        if ($user_id) {
            $email_template = $this->Template_Model->get_template_by_id($email_template_id);

            if ($email_template) {
                $subject      = $email_template['TEMPLATE_SUBJECT'];
                $message_body = $email_template['TEMPLATE_BODY'];
            } else {
                $subject = "🌟 Welcome to Empower Your Destiny 🌟";
                $message_body = "
                    <p>Dear <strong>$name</strong>,</p>
                    <p>Welcome to our community!</p>
                    <p><strong>Username:</strong> $email</p>
                    <p><strong>Password:</strong> $hashed_password</p>";
            }

            $message_body = str_replace(
                [
                    '{{name}}', '$Name$', '$NAME$',
                    '{{email}}', '$Email$', '$EMAIL$',
                    '{{password}}',
                    '{{training_dates}}',
                    '{{training_time}}',
                    '{{training_link}}',
                    '{{gift_id}}', '{gift_id}'
                ],
                [
                    $name, $name, $name,
                    $email, $email, $email,
                    $hashed_password,
                    $training_dates,
                    $training_time,
                    $training_link,
                    $gift_id, $gift_id
                ],
                $message_body
            );

            // Signature block with hardcoded image
            $signature_html = '<br><br><div style="display:inline-block;">';
            $signature_html .= '<img src="https://eyd.franhive.com/uploads/headshot4.png" style="max-width:200px;max-height:100px;display:block;">';

            if (!empty($email_template) && !empty($email_template['TEMPLATE_SIGN'])) {
                $signature_html .= '<div style="margin-top:2px;">' . nl2br($email_template['TEMPLATE_SIGN']) . '</div>';
            }
            $signature_html .= '</div>';

            $message_body .= $signature_html;

            // Prepare attachment paths
            $attachment_paths = [];
            if (!empty($email_template) && !empty($email_template['ATTACHMENTS'])) {
                $attachments = explode(',', $email_template['ATTACHMENTS']);
                foreach ($attachments as $file) {
                    $full_path = FCPATH . 'uploads/' . trim($file);
                    if (file_exists($full_path)) {
                        $attachment_paths[] = $full_path;
                    }
                }
            }

            // Send email to user
            $sent = $send_email($subject, $message_body, $attachment_paths);

            // ✅ NEW: Send receipt email to admin (only if user email sent successfully)
            if ($sent) {
                
                $admin_subject = " Registered New User".$landing_page_name;
                $admin_body = "
                    <p><A New user has registred From Landing page - strong>{$landing_page_name}</strong></p>
                    <p><strong>Status:</strong> New User</p>
                    <p><strong>Name:</strong> {$name}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Mobile:</strong> {$mobile}</p>
                    <p><strong>Platform:</strong> {$platform}</p>
                   
                    <p><strong>Template ID:</strong> {$email_template_id}</p>
                    <p><strong>Date/Time:</strong> " . date('Y-m-d H:i:s') . "</p>
                ";
                $send_admin_email($admin_subject, $admin_body);
            }
        }

    } else {
        $email_template = $this->Template_Model->get_template_by_id($email_template_id);

        if ($email_template) {
            $subject      = $email_template['TEMPLATE_SUBJECT'];
            $message_body = $email_template['TEMPLATE_BODY'];
        } else {
            $subject = "🌟 Welcome Back to 5 Days Conscious Upgrade 🌟";
            $message_body = "
                <p>Dear <strong>$name</strong>,</p>
                <p>Welcome back to our community!</p>
                <p><strong>Username:</strong> $email</p>
                <p><strong>Password:</strong> $hashed_password</p>";
        }

        // Updated replacements (includes gift_id + $Name$ support)
        $message_body = str_replace(
            [
                '{{name}}', '$Name$', '$NAME$',
                '{{email}}', '$Email$', '$EMAIL$',
                '{{password}}',
                '{{training_dates}}',
                '{{training_time}}',
                '{{training_link}}',
                '{{gift_id}}', '{gift_id}'
            ],
            [
                $name, $name, $name,
                $email, $email, $email,
                $hashed_password,
                $training_dates,
                $training_time,
                $training_link,
                $gift_id, $gift_id
            ],
            $message_body
        );

        $signature_html = '<br><br><div style="display:inline-block;">';
        $signature_html .= '<img src="https://eyd.franhive.com/uploads/headshot4.png" style="max-width:200px;max-height:100px;display:block;">';

        if (!empty($email_template) && !empty($email_template['TEMPLATE_SIGN'])) {
            $signature_html .= '<div style="margin-top:2px;">' . nl2br($email_template['TEMPLATE_SIGN']) . '</div>';
        }
        $signature_html .= '</div>';

        $message_body .= $signature_html;

        $attachment_paths = [];
        if (!empty($email_template) && !empty($email_template['ATTACHMENTS'])) {
            $attachments = explode(',', $email_template['ATTACHMENTS']);
            foreach ($attachments as $file) {
                $full_path = FCPATH . 'uploads/' . trim($file);
                if (file_exists($full_path)) {
                    $attachment_paths[] = $full_path;
                }
            }
        }

        // Send email to user
        $sent = $send_email($subject, $message_body, $attachment_paths);

        // ✅ NEW: Send receipt email to admin (only if user email sent successfully)
        if ($sent) {
            $admin_subject = "Registred Existing User- ".$landing_page_name;
            $admin_body = "
               <p><An Existing user has registred From Landing page - strong>{$landing_page_name}</strong></p>
                <p><strong>Status:</strong> Existing User</p>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Mobile:</strong> {$mobile}</p>
                <p><strong>Platform:</strong> {$platform}</p>
             
                <p><strong>Template ID:</strong> {$email_template_id}</p>
                <p><strong>Date/Time:</strong> " . date('Y-m-d H:i:s') . "</p>
            ";
            $send_admin_email($admin_subject, $admin_body);
        }
    }

    $contact_data = [
        'name'     => $name,
        'mobile'   => $mobile,
        'email'    => $email,
        'platform' => $platform,
        'gift_id'  => $gift_id,
        'country_code' => $country_code,
    ];

    try {
        if ($this->LandingPage_Model->save_contact($contact_data)) {
            echo json_encode(['status' => 'success', 'message' => 'Thank you for contacting us!']);
            return;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save contact information.']);
            return;
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        return;
    }
}

    

    
}
