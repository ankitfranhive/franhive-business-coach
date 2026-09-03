<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

// Default route
$subdomain = explode('.', $_SERVER['HTTP_HOST'])[0];

switch ($subdomain) {
    case 'demo':
        $route['default_controller'] = 'admin'; // Controller for demo subdomain
        break;
    case 'eyd':
        $route['default_controller'] = 'admin'; // Controller for eyd subdomain
        break;
    default:
        $route['default_controller'] = 'welcome'; // Default controller
}



// $route['default_controller'] = 'admin';
$route['/'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['my-test'] = 'welcome/myTest';
$route['my-test-thankyou'] = 'welcome/thankYouPage';
$route['my-test/(:any)'] = 'welcome/myTestDashboard';
$route['my-test-mcq/(:any)'] = 'welcome/myTestDashboardMcq';


$route['dashbaord'] = 'admin';
$route['add-test'] = 'admin/addTest';
$route['edit-test/(:any)'] = 'admin/editTest';
$route['test'] = 'admin/getAllTests';
$route['test-reports'] = 'admin/getAllTestsReports';

$route['admin-test-report/(:num)/(:num)'] = 'admin/adminTestReport/$1/$2';


$route['my-test-report-pdf/(:num)'] = 'welcome/downloadReportPdf/$1';
$route['my-test-submit-eval/(:num)'] = 'welcome/submitEvaluation/$1';

$route['my-test-report/(:num)'] = 'welcome/viewTestReport/$1';



$route['questions'] = 'admin/getAllQuestions';
$route['add-question'] = 'admin/addQuestion';

$route['add-question-test/(:any)'] = 'admin/questionsListTest';

$route['save-question-test'] = 'admin/addQuestionTest';

$route['edit-quetsion/(:any)'] = 'admin/editQuestion';

$route['user-test'] = 'welcome/myTest';


$route['my-courses'] = 'welcome/myCourses';

$route['save-test-response'] = 'welcome/saveTestResponse';

$route['test-allocation'] = 'admin/testAllocation';

$route['eval/(:any)'] = 'admin/testEval';

$route['save-user-eval'] = 'admin/saveUserTestEval';

$route['test-allocation/(:any)'] = 'admin/getAllTestsAllocation';

$route['save-test-user'] = 'admin/addTestUser';

$route['remove-test-user'] = 'admin/removeTestUser';

$route['delete-test'] = 'admin/deleteTest';

$route['my-reports'] = 'welcome/myTestReports';

$route['my-profile'] = 'admin/myProfile';

$route['help-support'] = 'admin/helpSupport';

$route['login'] = 'admin/userLogin';

$route['logout'] = 'admin/logout';

$route['register']['POST'] = 'admin/register';
$route['register']['GET'] = 'admin/userRegister';

$route['login-auth'] = 'admin/login';


$route['lead-dashboard'] = 'LeadController/leadDashboard';
$route['leads'] = 'LeadController/getAllLeads';
$route['add-lead'] = 'LeadController/addLead';
$route['lead-edit/(:num)'] = 'LeadController/editLead/$1';
$route['lead-delete/(:num)'] = 'LeadController/deleteLead/$1';
$route['change-status/(:num)'] = 'LeadController/changeStatus/$1';



$route['lead-status-list'] = 'LeadStatusController/getAllLeadStatus';
$route['add-lead-status'] = 'LeadStatusController/createLeadtStatus';
$route['view-lead-status/(:num)'] = 'LeadStatusController/viewLeadStatus/$1';
$route['edit-lead-status/(:num)'] = 'LeadStatusController/updateLeadStatus/$1';
$route['delete-lead-status/(:num)'] = 'LeadStatusController/deleteLeadStatus/$1';

$route['lead-source-list'] = 'LeadSourceController/getAllLeadSource';
$route['add-lead-source'] = 'LeadSourceController/createLeadtSource';
$route['view-lead-source/(:num)'] = 'LeadSourceController/viewLeadSource/$1';
$route['edit-lead-source/(:num)'] = 'LeadSourceController/updateLeadSource/$1';
$route['delete-lead-source/(:num)'] = 'LeadSourceController/deleteLeadSource/$1';

$route['lead-source-details-list'] = 'LeadSourceDetailsController/getAllLeadSourceDetails';
$route['add-lead-source-details'] = 'LeadSourceDetailsController/createLeadtSourceDetails';
$route['view-lead-source-details/(:num)'] = 'LeadSourceDetailsController/viewLeadSourceDetails/$1';
$route['edit-lead-source-details/(:num)'] = 'LeadSourceDetailsController/updateLeadSourceDetails/$1';
$route['delete-lead-source-details/(:num)'] = 'LeadSourceDetailsController/deleteLeadSourceDetails/$1';

$route['ratings-list'] = 'RatingsController/getAllRatings';
$route['add-rating'] = 'RatingsController/createRating';
$route['view-rating/(:num)'] = 'RatingsController/viewRating/$1';
$route['edit-rating/(:num)'] = 'RatingsController/updateRating/$1';
$route['delete-rating/(:num)'] = 'RatingsController/deleteRating/$1';

$route['business-type-list'] = 'BusinessTypeController/getAllBusinessType';
$route['add-business-type'] = 'BusinessTypeController/createBusinessType';
$route['view-business-type/(:num)'] = 'BusinessTypeController/viewBusinessType/$1';
$route['edit-business-type/(:num)'] = 'BusinessTypeController/updateBusinessType/$1';
$route['delete-business-type/(:num)'] = 'BusinessTypeController/deleteBusinessType/$1';

$route['landing-page-dashboard'] = 'LandingPageController/landingPageDashboard';
$route['landing-pages'] = 'LandingPageController/getAllLandingPages';
$route['add-landing-page'] = 'LandingPageController/addLandingPage';
$route['landing-page-view/(:num)'] = 'LandingPageController/viewLandingPage/$1';
$route['landing-page-edit/(:num)'] = 'LandingPageController/editLandingPage/$1';
$route['landing-page-delete/(:num)'] = 'LandingPageController/deleteLandingPage/$1';
// $route['(:any)/(:num)'] = 'LandingPageController/viewLandingPagePreview/$1/$2';

$route['videos-list/(:num)'] = 'LandingPageController/videoList/$1';
$route['add-video-page/(:num)'] = 'LandingPageController/addVideo/$1';
$route['video-page-edit/(:num)'] = 'LandingPageController/videoList/$1';
$route['video-page-delete/(:num)'] = 'LandingPageController/deleteLead/$1';

$route['web-forms-list'] = 'WebFormController/getAllWebForms';
$route['add-web-form'] = 'WebFormController/createNewWebForm';
$route['edit-web-form/(:num)'] = 'WebFormController/updateWebForm/$1';
$route['view-web-form/(:num)'] = 'WebFormController/viewWebForm/$1';
$route['delete-web-form/(:num)'] = 'WebFormController/deleteWebForm/$1';

$route['ticket-list'] = 'TicketController/getAllTicketsData';
$route['add-ticket'] = 'TicketController/createTicket';
$route['edit-ticket/(:num)'] = 'TicketController/updateTicket/$1';
$route['view-ticket/(:num)'] = 'TicketController/viewTicket/$1';
$route['delete-ticket/(:num)'] = 'TicketController/deleteTicket/$1';

$route['ticket-department-list'] = 'TicketDepartmentController/getAllTicketDepartmentData';
$route['add-ticket-department'] = 'TicketDepartmentController/createTicketDepartment';
$route['edit-ticket-department/(:num)'] = 'TicketDepartmentController/updateTicketDepartment/$1';
$route['view-ticket-department/(:num)'] = 'TicketDepartmentController/viewTicketDepartment/$1';
$route['delete-ticket-department/(:num)'] = 'TicketDepartmentController/deleteTicketDepartment/$1';

$route['ticket-status-list'] = 'TicketStatusController/getAllTicketStatus';
$route['add-ticket-status'] = 'TicketStatusController/createTicketStatus';
$route['edit-ticket-status/(:num)'] = 'TicketStatusController/updateTicketStatus/$1';
$route['view-ticket-status/(:num)'] = 'TicketStatusController/viewTicketStatus/$1';
$route['delete-ticket-status/(:num)'] = 'TicketStatusController/deleteTicketStatus/$1';

$route['ticket-priority-list'] = 'TicketPriorityController/getAllTicketPriority';
$route['add-ticket-priority'] = 'TicketPriorityController/createTicketPriority';
$route['edit-ticket-priority/(:num)'] = 'TicketPriorityController/updateTicketPriority/$1';
$route['view-ticket-priority/(:num)'] = 'TicketPriorityController/viewTicketPriority/$1';
$route['delete-ticket-priority/(:num)'] = 'TicketPriorityController/deleteTicketPriority/$1';

$route['welcome-note-text-list'] = 'BasicConfigController/getAllWelcomeNoteText';
$route['add-welcome-note-text'] = 'BasicConfigController/createWelcomeNoteText';
$route['edit-welcome-note-text/(:num)'] = 'BasicConfigController/updateWelcomeNoteText/$1';
$route['view-welcome-note-text/(:num)'] = 'BasicConfigController/viewWelcomeNoteText/$1';
$route['delete-welcome-note-text/(:num)'] = 'BasicConfigController/deleteWelcomeNoteText/$1';

$route['client-dashboard'] = 'ClientController/clientDashboard';
$route['clients'] = 'ClientController/clientList';
$route['add-client'] = 'ClientController/createClient';

$route['client-type-list'] = 'ClientTypeController/clientTypeList';
$route['add-client-type'] = 'ClientTypeController/createType';
$route['edit-client-type/(:num)'] = 'ClientTypeController/updateType/$1';
$route['view-client-type/(:num)'] = 'ClientTypeController/clientTypeView/$1';
$route['delete-client-type/(:num)'] = 'ClientTypeController/deleteClientType/$1';







$route['tasks'] = 'LeadController/getAllTasks';

$route['add-task'] = 'LeadController/addFreshTask';

$route['knowledge-center'] = 'KnowldegeCenterController/getAllTrainingCourses';
$route['add-training-course'] = 'KnowldegeCenterController/addTraingCourse';
$route['knowledge-center/view-course/(:num)'] = 'KnowldegeCenterController/viewCourse/$1';
$route['knowledge-center/edit-course/(:num)'] = 'KnowldegeCenterController/editCourse/$1';
$route['knowledge-center/delete-course/(:num)'] = 'KnowldegeCenterController/deleteCourse/$1';

$route['training-course-lesson'] = 'KnowldegeCenterController/getAllTrainingCoursesLessons';
$route['add-training-course-lesson'] = 'KnowldegeCenterController/addTraingCourseLesson';
$route['knowledge-center/view-course-lesson/(:num)'] = 'KnowldegeCenterController/viewCourseLesson/$1';
$route['knowledge-center/edit-course-lesson/(:num)'] = 'KnowldegeCenterController/editCourseLesson/$1';
$route['knowledge-center/delete-course-lesson/(:num)'] = 'KnowldegeCenterController/deleteCourseLesson/$1';

$route['lesson-allocation'] = 'KnowldegeCenterController/lessonAllocation';

$route['lesson-allocation/(:any)'] = 'KnowldegeCenterController/getAllCoursesAllocation';
$route['save-lesson-user'] = 'KnowldegeCenterController/addLessonUser';
$route['remove-lesson-user'] = 'KnowldegeCenterController/removeLessonUser';

$route['my-lessons/(:any)'] = 'KnowldegeCenterController/myLessons';

$route['knowledge-center/view-my-course-lesson/(:num)'] = 'KnowldegeCenterController/viewMyCourseLesson/$1';




$route['campaign-dashboard'] = 'CampaignController/campaignDashboard';
$route['campaigns'] = 'CampaignController/getAllCampaign';
$route['view-campaign/(:num)'] = 'CampaignController/viewCampaign/$1';
$route['add-campaign'] = 'CampaignController/addCampaign';
$route['edit-campaign/(:num)'] = 'CampaignController/editCampaign/$1';
$route['delete-campaign/(:num)'] = 'CampaignController/deleteCampaign/$1';


$route['templates'] = 'CampaignController/getAllTemplates';
$route['add-template'] = 'CampaignController/addTemplate';
$route['edit-template/(:num)'] = 'CampaignController/editTemplate/$1';
$route['view-template/(:num)'] = 'CampaignController/viewTemplate/$1';
$route['delete-template/(:num)'] = 'CampaignController/deleteTemplate/$1';

$route['lead-tasks'] = 'CalenderController/getHolidayCalender';
$route['client-tasks'] = 'CalenderController/getClientTaskData';

$route['calendar'] = 'CalenderController/index';
$route['calendar/events'] = 'CalenderController/events';



$route['users'] = 'UserController/getAllUsers';
$route['add-user']['GET'] = 'UserController/addUser';
$route['add-user']['POST'] = 'UserController/save';
$route['edit-user/(:num)'] = 'UserController/editUser/$1';


$route['permissions'] = 'PermissionController/index';
$route['add-permission']['POST'] = 'PermissionController/save';
$route['add-permission']['GET'] = 'PermissionController/store';
$route['view-permission/(:num)'] = 'PermissionController/show/$1';
$route['edit-permission/(:num)']['POST'] = 'PermissionController/update/$1';
$route['edit-permission/(:num)']['GET'] = 'PermissionController/edit/$1';
$route['delete-permission/(:num)']['GET'] = 'PermissionController/delete/$1';

$route['modules'] = 'ModuleController/index';
$route['add-module']['POST'] = 'ModuleController/save';
$route['add-module']['GET'] = 'ModuleController/store';
$route['view-module/(:num)']['GET'] = 'ModuleController/show/$1';
$route['edit-module/(:num)']['POST'] = 'ModuleController/update/$1';
$route['edit-module/(:num)']['GET'] = 'ModuleController/edit/$1';
$route['delete-module/(:num)']['GET'] = 'ModuleController/delete/$1';

$route['roles'] = 'RoleController/index';
$route['roles/(:num)']['GET'] = 'RoleController/show/$1';
$route['add-role']['POST'] = 'RoleController/save';
$route['add-role']['GET'] = 'RoleController/store';
$route['edit-role/(:num)']['POST'] = 'RoleController/update/$1';
$route['edit-role/(:num)']['GET'] = 'RoleController/edit/$1';
$route['delete-role/(:num)']['GET'] = 'RoleController/delete/$1';

$route['(:any)/(:num)'] = 'LandingPageController/viewLandingPagePreview/$1/$2';
// $route['role/(:num)/activate/'] = 'RoleController/activate/$1';
// $route['role/(:num)/deactivate/'] = 'RoleController/deactivate/$1';
// $route['role/(:num)/assign-permission']['PUT'] = 'RoleController/assignPermission/$1';
// $route['role/(:num)/revoke-permission/(:permission_id)'] = 'RoleController/revokePermission/$1';


// $route['payment-agreement'] = 'welcome/paymentAgreement';
// $route['payment_agreement_list'] = 'welcome/paymentAgreementList';

$route['payment-agreement/send'] = 'PaymentAgreementController/sendPaymentAgreement';
$route['payment-agreement/resend'] = 'PaymentAgreementController/resendPaymentAgreement';
$route['payment-agreement/requests'] = 'PaymentAgreementController/paymentAgreementRequests';
$route['payment-agreement/view/(:num)'] = 'PaymentAgreementController/viewPaymentAgreement/$1';
$route['payment-agreement/update-approval/(:num)'] = 'PaymentAgreementController/updateAgreementApproval/$1';

$route['payment-agreement/form/(:any)'] = 'PaymentAgreementController/paymentAgreementForm/$1';
$route['payment-agreement/submit/(:any)'] = 'PaymentAgreementController/enroll_data_submit/$1';
$route['payment-agreement/preview/(:num)'] = 'PaymentAgreementController/previewAgreement/$1';
$route['payment-agreement/download/(:num)'] = 'PaymentAgreementController/downloadAgreementPDF/$1';

$route['payment-agreement/form-settings'] = 'PaymentAgreementController/formFieldSettings';
$route['payment-agreement/save-form-settings'] = 'PaymentAgreementController/saveFormFieldSettings';


// application/config/routes.php
$route['PaymentController/validate_promo']         = 'PaymentController/validate_promo';
$route['PaymentController/create_checkout_session'] = 'PaymentController/create_checkout_session';
$route['payment/success'] = 'PaymentController/success';
$route['payment/cancel']  = 'PaymentController/cancel';



$route['telephony'] = 'Telephony/index';
$route['telephony/sidebar'] = 'Telephony/sidebar';


$route['webhooks/cloudtalk'] = 'Cloudtalk_webhook/receive';
$route['call-logs']          = 'Call_logs/index';
$route['call-logs/data']  = 'Call_logs/data'; 


// Forgot password
$route['forgot-password']          = 'auth/forgot_password';
$route['forgot-password/submit']   = 'auth/forgot_password_submit';
$route['reset-password/(:any)']    = 'auth/reset_password/$1';
$route['reset-password/submit']    = 'auth/reset_password_submit';


// eForms Admin
$route['admin_eforms/templates']               = 'AdminEforms/templates';
$route['admin_eforms/template_create']         = 'AdminEforms/template_create';
$route['admin_eforms/template_store']          = 'AdminEforms/template_store';
$route['admin_eforms/message']                 = 'AdminEforms/message';
$route['admin_eforms/form_types']              = 'AdminEforms/form_types';
$route['admin_eforms/form_type_create']        = 'AdminEforms/form_type_create';
$route['admin_eforms/form_type_store']         = 'AdminEforms/form_type_store';
$route['admin_eforms/form_type_edit/(:num)']   = 'AdminEforms/form_type_edit/$1';
$route['admin_eforms/form_type_update/(:num)'] = 'AdminEforms/form_type_update/$1';

$route['admin_eforms/ajax_form_type_fields/(:num)'] = 'AdminEforms/ajax_form_type_fields/$1';

$route['admin_eforms/send_form/(:num)']        = 'AdminEforms/send_form/$1';
$route['admin_eforms/send_iict_form']          = 'AdminEforms/send_iict_form';
$route['admin_eforms/iict_form_edit']          = 'AdminEforms/iict_form_edit';
$route['admin_eforms/iict_form_update']         = 'AdminEforms/iict_form_update';
$route['admin_eforms/requests']                = 'AdminEforms/requests';

// eForms Client
$route['eforms/fill/(:any)']                   = 'Eforms/fill/$1';
$route['eforms/open/(:any)']                   = 'Eforms/open/$1';
$route['eforms/iict_agreement']                = 'Eforms/iict_agreement';


$route['admin_eforms/template_edit/(:num)']   = 'AdminEforms/template_edit/$1';
$route['admin_eforms/template_update/(:num)'] = 'AdminEforms/template_update/$1';
$route['admin_eforms/template_delete/(:num)'] = 'AdminEforms/template_delete/$1';


$route['admin_eforms/submissions'] = 'AdminEforms/submissions';
$route['admin_eforms/submission_view/(:num)'] = 'AdminEforms/submission_view/$1';
$route['admin_eforms/download_pdf/(:num)'] = 'AdminEforms/download_pdf/$1';

$route['admin_eforms/thank_you_templates']              = 'AdminEforms/thank_you_templates';
$route['admin_eforms/thank_you_template_create']        = 'AdminEforms/thank_you_template_create';
$route['admin_eforms/thank_you_template_store']         = 'AdminEforms/thank_you_template_store';
$route['admin_eforms/thank_you_template_edit/(:num)']   = 'AdminEforms/thank_you_template_edit/$1';
$route['admin_eforms/thank_you_template_update/(:num)'] = 'AdminEforms/thank_you_template_update/$1';
$route['admin_eforms/thank_you_template_delete/(:num)'] = 'AdminEforms/thank_you_template_delete/$1';


$route['admin_media/images']        = 'AdminMedia/images';
$route['admin_media/upload_image']  = 'AdminMedia/upload_image';

$route['admin_media/delete_image'] = 'AdminMedia/delete_image';














// $route->post('webhooks/cloudtalk', 'CloudTalkWebhook::handle', ['filter' => 'ctsecret']);



