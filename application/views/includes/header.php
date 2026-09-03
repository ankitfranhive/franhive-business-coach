<head>
	<!-- Basic Page Info -->
	<meta charset="utf-8" />
	<title>Franhive- One Stop Solution to manage your business.</title>

	<!-- Site favicon -->
	<link rel="apple-touch-icon" sizes="180x180" href="https://www.franhive.com/images/favicon.ico" />
	<link rel="icon" type="image/png" sizes="32x32" href="https://www.franhive.com/images/favicon.ico" />
	<link rel="icon" type="image/png" sizes="16x16" href="https://www.franhive.com/images/favicon.ico" />

	<!-- Mobile Specific Metas -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

	<!-- Google Font -->
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/core.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/icon-font.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('src/plugins/datatables/css/dataTables.bootstrap4.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('src/plugins/datatables/css/responsive.bootstrap4.min.css'); ?>" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('vendors/styles/style.css'); ?>" />

	<link rel="stylesheet" href="https://unpkg.com/tippy.js/dist/tippy.css" />
	<script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy.all.min.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<style>

		/* Time block style */
.header-time {
    font-size: 13px;
    color: #555;
}

.header-time .dw.dw-clock1 {
    font-size: 16px;
}

.header-tz-label {
    font-size: 11px;
    color: #999;
}

/* Wrapper around bell + badge */
.notification-wrapper {
    position: relative;
    display: inline-block;
}

/* Count badge */
.notification-count {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ff4d4d;
    color: #fff;
    font-size: 10px;
    padding: 1px 4px;
    border-radius: 50%;
    min-width: 14px;
    height: 14px;
    line-height: 14px;
    text-align: center;
    z-index: 10;
}

/* Notification text wrapping */
.notification-content {
    white-space: normal;
    word-break: break-word;
}

.notification-list .dropdown-item {
    white-space: normal;
    padding: 10px 15px;
}


		/* Wrapper around bell + badge */
.notification-wrapper {
    position: relative;
    display: inline-block;
}

/* Count badge */
.notification-count {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #ff4d4d;
    color: #fff;
    font-size: 10px;
    padding: 1px 4px;
    border-radius: 50%;
    min-width: 14px;
    height: 14px;
    line-height: 14px;
    text-align: center;
    z-index: 10;
}

/* Ensure notification text wraps and isn't cut off */
.notification-content {
    white-space: normal;
    word-break: break-word;
}

.notification-list .dropdown-item {
    white-space: normal;
    padding: 10px 15px;
}


  /* Table truncation helpers */
  .table td:not(.no-wrap), .table th:not(.no-wrap) {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 150px; /* Adjust as needed */
  }
  .no-wrap {
    white-space: normal;
    overflow: visible;
    text-overflow: unset;
  }

  /* Brand bar — compact, no overlap */
  .brand-logo{
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;               /* space between logo and caption */
    width: 280px;
    min-height: 70px;       /* was height: 70px; -> now flexible */
    padding: 8px 10px;
    border-bottom: 1px solid rgba(0, 0, 0, .06);
  }
  .brand-logo__link{ display:flex; flex-direction:column; align-items:center; text-decoration:none; }
  .brand-logo__img{ max-height:42px; width:auto; object-fit:contain; }

  <?php if (SUBDOMAIN == "eyd"){ ?>
    .brand-logo { background: #000; }
    /* caption color for dark bg */
    .powered-mini{ color:#cbd5e1; }
  <?php } else { ?>
    .brand-logo { background: #fff !important; }
    /* caption color for light bg */
    .powered-mini{ color:#94a3b8; }
  <?php } ?>

  /* Tiny "Powered by" caption */
  .powered-mini{
    display:inline-flex; align-items:center; gap:6px;
    font-size:10.5px; line-height:1; letter-spacing:.2px; margin-top:4px;
    opacity:.95;
  }
  .powered-mini .dot{
    width:6px; height:6px; border-radius:50%;
    background:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.15);
  }

  /* Make sure the close icon doesn’t shift layout */
  .brand-logo .close-sidebar{ position:absolute; right:10px; top:10px; }

  /* Keep submenu open when PHP adds .show */
  .sidebar-menu .submenu.show { display: block; }

  /* Active state styling */
  #accordion-menu a.active { color: #ffc107; font-weight: 700; }
  #accordion-menu li.dropdown.active > a { color: #ffc107; }

  /* Ensure menu content never hides under brand area */
  .menu-block{ padding-top:6px; }

  /* Slightly tighter on smaller screens */
  @media (max-width: 1280px){
    .brand-logo{ width:240px; }
    .brand-logo__img{ max-height:38px; }
    .powered-mini{ font-size:10px; }
  }
</style>

</head>

<?php
// Guard: require login
if (!$this->session->userdata('logged_in')) {
	redirect('login');
}

// Ensure session library
if (!isset($this->session)) {
	$this->load->library('session');
}

$this->load->model('Permission_Model');

// Permissions: currently stored as comma-separated string in session
$permissions = $this->session->userdata('permissions');

// Build mapping from comma-separated IDs (e.g., "1,2,3")
$permission_ids_array = is_string($permissions) ? explode(',', $permissions) : (array)$permissions;
$permission_mappings = array_map('trim', $permission_ids_array);

/** Permission check — currently: allow access if tab_id exists in permission_mappings */
function hasPermission($permission_mappings, $tab_id, $action) {
	if ($action === 'CAN_ACCESS' && in_array($tab_id, (array)$permission_mappings, true)) {
		return true;
	}
	return false;
}

/* ================= Active menu helpers (server-side) ================= */

/** Current request path (no leading/trailing slash) */
function current_uri() {
	$ci = &get_instance();
	if (isset($ci->uri)) {
		return trim($ci->uri->uri_string(), '/');
	}
	$u = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
	return trim($u, '/');
}

/** True if current URI starts with any of the provided paths */
function uri_is($paths) {
	$uri = current_uri();
	foreach ((array)$paths as $p) {
		$p = trim((string)$p, '/');
		if ($p !== '' && stripos($uri, $p) === 0) return true;
	}
	return false;
}

/** Return 'active' if current URI matches any */
function active_if($paths) { return uri_is($paths) ? 'active' : ''; }

/** Return 'show' (keep submenu open) if current URI matches any */
function show_if($paths) { return uri_is($paths) ? 'show' : ''; }
?>

<body>
<div class="header">
    <div class="header-left">
        <div class="menu-icon bi bi-list"></div>
    </div>
   <!-- ⏰ Current Time (India / Philippines / Melbourne) -->
<div class="header-time d-none d-md-flex align-items-center mr-3">
    <i class="dw dw-clock1 mr-2"></i>

    <div class="d-flex flex-column" style="line-height: 1.1;">
        <div>
            <strong>India:</strong> <span id="timeIndia">--:--:--</span>
            <small class="ml-1 text-muted">(IST)</small>
        </div>
        <div>
            <strong>Philippines:</strong> <span id="timePH">--:--:--</span>
            <small class="ml-1 text-muted">(PHT)</small>
        </div>
        <div>
            <strong>Melbourne:</strong> <span id="timeMel">--:--:--</span>
            <small class="ml-1 text-muted">(AET)</small>
        </div>
    </div>
</div>


        <!-- 🔔 Notification bell (LEFT of settings icon) -->
       <!-- 🔔 Notification bell (LEFT of settings icon) -->
<div class="user-notification pr-3">
    <div class="dropdown">
        <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
            <span class="notification-wrapper">
                <i class="dw dw-notification" style="font-size:20px;"></i>
                <!-- Use custom class, NOT notification-active -->
                <span class="notification-count">2</span>
            </span>
        </a>

        <div class="dropdown-menu dropdown-menu-right" style="min-width: 320px;">
            <div class="notification-list" style="max-height: 350px; overflow-y: auto;">

                <!-- Notification 1 -->
                <a class="dropdown-item d-flex align-items-start" href="javascript:void(0);">
                    <div class="notification-icon mr-2">
                        <i class="dw dw-bell1"></i>
                    </div>
                    <div class="notification-content">
                        <h6 class="notification-title mb-1">My Tasks</h6>
                        <p class="mb-0">
                            Please check your My Task section to get informations about tasks.
                        </p>
                    </div>
                </a>

                <!-- Notification 2 -->
                <a class="dropdown-item d-flex align-items-start" href="javascript:void(0);">
                    <div class="notification-icon mr-2">
                        <i class="dw dw-bell1"></i>
                    </div>
                    <div class="notification-content">
                        <h6 class="notification-title mb-1">My Training</h6>
                        <p class="mb-0">
                            We have set My Course and My Report under My Training sections.
                        </p>
                    </div>
                </a>

            </div>
        </div>
    </div>
</div>

        <!-- /Notification bell -->

		

        <!-- Existing settings icon -->
        <div class="dashboard-setting user-notification">
            <div class="dropdown">
                <a class="dropdown-toggle no-arrow" href="javascript:;" data-toggle="right-sidebar">
                    <i class="dw dw-settings2"></i>
                </a>
            </div>
        </div>

        <!-- Existing user dropdown -->
        <div class="user-info-dropdown">
            <div class="dropdown">
                <a class="dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                    <span class="user-icon">
                        <img src="<?php echo isset($_SESSION['PROFILE_PICTURE']) ? $_SESSION['PROFILE_PICTURE'] : base_url('vendors/images/user_avtar.png'); ?>" alt="" />
                    </span>
                    <span class="user-name">
                        <?php echo isset($_SESSION['user']['NAME']) ? $_SESSION['user']['NAME'] : 'NA' ?>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                    <a class="dropdown-item" href="<?php echo base_url() ?>my-profile"><i class="dw dw-user1"></i> Profile</a>
                    <a class="dropdown-item" href="<?php echo base_url() ?>help-support"><i class="dw dw-help"></i> Help</a>
                    <a class="dropdown-item" href="<?php echo base_url() ?>logout"><i class="dw dw-logout"></i> Log Out</a>
                </div>
            </div>
        </div>

    </div>
</div>


	<div class="right-sidebar">
		<div class="sidebar-title">
			<h3 class="weight-600 font-16 text-blue">
				Layout Settings
				<span class="btn-block font-weight-400 font-12">User Interface Settings</span>
			</h3>
			<div class="close-sidebar" data-toggle="right-sidebar-close">
				<i class="icon-copy ion-close-round"></i>
			</div>
		</div>
		<div class="right-sidebar-body customscroll">
			<div class="right-sidebar-body-content">
				<h4 class="weight-600 font-18 pb-10">Header Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary header-white active">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary header-dark">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
				<div class="sidebar-btn-group pb-30 mb-10">
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-light">White</a>
					<a href="javascript:void(0);" class="btn btn-outline-primary sidebar-dark active">Dark</a>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu Dropdown Icon</h4>
				<div class="sidebar-radio-group pb-10 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-1" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-1" checked />
						<label class="custom-control-label" for="sidebaricon-1"><i class="fa fa-angle-down"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-2" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-2" />
						<label class="custom-control-label" for="sidebaricon-2"><i class="ion-plus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebaricon-3" name="menu-dropdown-icon" class="custom-control-input" value="icon-style-3" />
						<label class="custom-control-label" for="sidebaricon-3"><i class="fa fa-angle-double-right"></i></label>
					</div>
				</div>

				<h4 class="weight-600 font-18 pb-10">Menu List Icon</h4>
				<div class="sidebar-radio-group pb-30 mb-10">
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-1" name="menu-list-icon" class="custom-control-input" value="icon-list-style-1" checked />
						<label class="custom-control-label" for="sidebariconlist-1"><i class="ion-minus-round"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-2" name="menu-list-icon" class="custom-control-input" value="icon-list-style-2" />
						<label class="custom-control-label" for="sidebariconlist-2"><i class="fa fa-circle-o" aria-hidden="true"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-3" name="menu-list-icon" class="custom-control-input" value="icon-list-style-3" />
						<label class="custom-control-label" for="sidebariconlist-3"><i class="dw dw-check"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-4" name="menu-list-icon" class="custom-control-input" value="icon-list-style-4" checked />
						<label class="custom-control-label" for="sidebariconlist-4"><i class="icon-copy dw dw-next-2"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-5" name="menu-list-icon" class="custom-control-input" value="icon-list-style-5" />
						<label class="custom-control-label" for="sidebariconlist-5"><i class="dw dw-fast-forward-1"></i></label>
					</div>
					<div class="custom-control custom-radio custom-control-inline">
						<input type="radio" id="sidebariconlist-6" name="menu-list-icon" class="custom-control-input" value="icon-list-style-6" />
						<label class="custom-control-label" for="sidebariconlist-6"><i class="dw dw-next"></i></label>
					</div>
				</div>

				<div class="reset-options pt-30 text-center">
					<button class="btn btn-danger" id="reset-settings">Reset Settings</button>
				</div>
			</div>
		</div>
	</div>

	<div class="left-side-bar">
	<div class="brand-logo <?php echo (SUBDOMAIN == 'eyd') ? 'brand-logo--eyd' : 'brand-logo--default'; ?>">
  <a href="<?= base_url('/dashbaord'); ?>" class="brand-logo__link">
    <?php if (SUBDOMAIN == "eyd"){ ?>
      <!-- EYD Logo -->
      <img src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png" alt="EYD Logo" class="brand-logo__img" />
      <div class="powered-mini"><span class="dot"></span><span>Powered by <strong>Franhive</strong></span></div>
    <?php } else { ?>
      <!-- Franhive Logo -->

      <img src="https://eyd.franhive.com/uploads/70d631f14dbe43e3c32c502c037ad94b.png" alt="Franhive Logo" class="brand-logo__img" />
      <div class="powered-mini"><span class="dot"></span><span>Powered by <strong>Franhive</strong></span></div>
    <?php } ?>
  </a>
  <div class="close-sidebar" data-toggle="left-sidebar-close">
    <i class="ion-close-round"></i>
  </div>
</div>


		<div class="menu-block customscroll">
			<div class="sidebar-menu">
				<ul id="accordion-menu">

					<?php if (hasPermission($permission_mappings, '1', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if(['dashbaord','dashboard']) ?>">
						<a href="<?= base_url('/dashbaord'); ?>" class="dropdown-toggle no-arrow <?= active_if(['dashbaord','dashboard']) ?>">
							<span class="micon bi bi-house"></span><span class="mtext">Dashboard</span>
						</a>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '2', 'CAN_ACCESS')) :
						$leadRoutes = [
							'lead-dashboard','leads','tasks','web-forms-list',
							'lead-status-list','business-type-list','lead-source-list',
							'lead-source-details-list','ratings-list'
						];
					?>
					<li class="dropdown <?= active_if($leadRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($leadRoutes) ?>">
							<span class="micon bi bi-sliders2-vertical"></span><span class="mtext">Lead Management</span>
						</a>
						<ul class="submenu <?= show_if($leadRoutes) ?>">
							<li><a class="<?= active_if('lead-dashboard') ?>" href="<?= base_url('/lead-dashboard'); ?>">Lead Dashboard</a></li>
							<li><a class="<?= active_if('leads') ?>" href="<?= base_url('/leads'); ?>">Add / View Leads</a></li>
							<li><a class="<?= active_if('tasks') ?>" href="<?= base_url('/tasks'); ?>">Tasks</a></li>
							<li><a class="<?= active_if('web-forms-list') ?>" href="<?= base_url('/web-forms-list'); ?>">Web Forms</a></li>

							<li>
								<a href="javascript:;" class="dropdown-toggle">Configuration</a>
								<ul class="submenu <?= show_if(['lead-status-list','business-type-list','lead-source-list','lead-source-details-list','ratings-list']) ?>">
									<li><a class="<?= active_if('lead-status-list') ?>" href="<?= base_url('/lead-status-list'); ?>">Lead Status</a></li>
									<li><a class="<?= active_if('business-type-list') ?>" href="<?= base_url('/business-type-list'); ?>">Business Type</a></li>
									<li><a class="<?= active_if('lead-source-list') ?>" href="<?= base_url('/lead-source-list'); ?>">Lead Source</a></li>
									<li><a class="<?= active_if('lead-source-details-list') ?>" href="<?= base_url('/lead-source-details-list'); ?>">Lead Source Details</a></li>
									<li><a class="<?= active_if('ratings-list') ?>" href="<?= base_url('/ratings-list'); ?>">Rating</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '3', 'CAN_ACCESS')) :
						$landingRoutes = ['landing-pages'];
					?>
					<li class="dropdown <?= active_if($landingRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($landingRoutes) ?>">
							<span class="micon bi bi-file-pdf"></span><span class="mtext">Landing Page</span>
						</a>
						<ul class="submenu <?= show_if($landingRoutes) ?>">
							<li><a class="<?= active_if('landing-pages') ?>" href="<?= base_url('/landing-pages'); ?>">Add / View Pages</a></li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '13', 'CAN_ACCESS')) :
						$clientRoutes = ['client-dashboard','clients','ClientController/getAllTasks','client-type-list'];
					?>
					<li class="dropdown <?= active_if($clientRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($clientRoutes) ?>">
							<span class="micon bi bi-textarea-resize"></span><span class="mtext">Clients Management</span>
						</a>
						<ul class="submenu <?= show_if($clientRoutes) ?>">
							<li><a class="<?= active_if('client-dashboard') ?>" href="<?= base_url('/client-dashboard'); ?>">Client's Dashboard</a></li>
							<li><a class="<?= active_if('clients') ?>" href="<?= base_url('/clients'); ?>">Add / View Clients</a></li>
							<li><a class="<?= active_if('ClientController/getAllTasks') ?>" href="<?= base_url('ClientController/getAllTasks'); ?>">Task</a></li>
							<li>
								<a href="javascript:;" class="dropdown-toggle">Configuration</a>
								<ul class="submenu <?= show_if('client-type-list') ?>">
									<li><a class="<?= active_if('client-type-list') ?>" href="<?= base_url('/client-type-list'); ?>">Client Type</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '4', 'CAN_ACCESS')) :
						$campaignRoutes = ['campaign-dashboard','campaigns','templates'];
					?>
					<li class="dropdown <?= active_if($campaignRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($campaignRoutes) ?>">
							<span class="micon bi bi-command"></span><span class="mtext">Campaign Center</span>
						</a>
						<ul class="submenu <?= show_if($campaignRoutes) ?>">
							<li><a class="<?= active_if('campaign-dashboard') ?>" href="<?= base_url('/campaign-dashboard'); ?>">Campaign's Dashboard</a></li>
							<li><a class="<?= active_if('campaigns') ?>" href="<?= base_url('/campaigns'); ?>">Add / View Campaigns</a></li>
							<li><a class="<?= active_if('templates') ?>" href="<?= base_url('/templates'); ?>">Templates</a></li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '5', 'CAN_ACCESS')) :
						$kcRoutes = ['knowledge-center','training-course-lesson','lesson-allocation'];
					?>
					<li class="dropdown <?= active_if($kcRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($kcRoutes) ?>">
							<span class="micon bi bi-hdd-stack"></span><span class="mtext">Knowledge Center</span>
						</a>
						<ul class="submenu <?= show_if($kcRoutes) ?>">
							<li><a class="<?= active_if('knowledge-center') ?>" href="<?= base_url('knowledge-center'); ?>">Training Courses</a></li>
							<li><a class="<?= active_if('training-course-lesson') ?>" href="<?= base_url('training-course-lesson'); ?>">Lessons</a></li>
							<li><a class="<?= active_if('lesson-allocation') ?>" href="<?= base_url('/lesson-allocation'); ?>">Course Allocation</a></li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '6', 'CAN_ACCESS')) :
						$calRoutes = ['lead-tasks','client-tasks'];
					?>
					<li class="dropdown <?= active_if($calRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($calRoutes) ?>">
							<span class="micon bi bi-calendar4-week"></span><span class="mtext">Calendar</span>
						</a>
						<ul class="submenu <?= show_if($calRoutes) ?>">
							<li><a class="<?= active_if('lead-tasks') ?>" href="<?= base_url('/lead-tasks'); ?>">Holiday Calendar</a></li>
							<li><a class="<?= active_if('client-tasks') ?>" href="<?= base_url('/client-tasks'); ?>">Task Calendar</a></li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '7', 'CAN_ACCESS')) :
						$testRoutes = ['test','questions','test-allocation','test-reports'];
					?>
					<li class="dropdown <?= active_if($testRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($testRoutes) ?>">
							<span class="micon bi bi-file-pdf"></span><span class="mtext">Test Management</span>
						</a>
						<ul class="submenu <?= show_if($testRoutes) ?>">
							<li><a class="<?= active_if('test') ?>" href="<?= base_url('/test'); ?>">Add/View Test</a></li>
							<li><a class="<?= active_if('questions') ?>" href="<?= base_url('/questions'); ?>">Add/View Question</a></li>
							<li><a class="<?= active_if('test-allocation') ?>" href="<?= base_url('/test-allocation'); ?>">Test Allocation</a></li>
							<li><a class="<?= active_if('test-reports') ?>" href="<?= base_url('/test-reports'); ?>">Test Evaluation</a></li>
						</ul>
					</li>
					<?php endif; ?>

				

					<?php if (hasPermission($permission_mappings, '9', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if(['telephony']) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if(['telephony']) ?>">
							<span class="micon bi bi-textarea-resize"></span><span class="mtext">Cloud Talk</span>
						</a>
						<ul class="submenu <?= show_if(['telephony']) ?>">
							<li><a class="<?= active_if('telephony') ?>" href="<?= base_url('/telephony'); ?>">Recent Call logs</a></li>
						</ul>
					</li>
					<?php endif; ?>

					<?php if (hasPermission($permission_mappings, '9', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if('payment-agreement/requests') ?>">
						<a href="<?= base_url('payment-agreement/requests'); ?>" class="dropdown-toggle no-arrow <?= active_if('payment-agreement/requests') ?>">
							<span class="micon bi bi-file-pdf"></span>
							<span class="mtext">Payment Agreement</span>
						</a>
					</li>
				<?php endif; ?>


					<?php if (hasPermission($permission_mappings, '10', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if(['my-courses','my-reports']) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if(['my-courses','my-reports']) ?>">
							<span class="micon bi bi-textarea-resize"></span><span class="mtext">My Trainings</span>
						</a>
						<ul class="submenu <?= show_if(['my-courses','my-reports']) ?>">

						
							<li><a href="<?= base_url('/my-courses'); ?>" class="dropdown-toggle no-arrow <?= active_if('my-courses') ?>">MY COURSES</a></li>
							<li><a href="<?= base_url('/my-reports'); ?>" class="dropdown-toggle no-arrow <?= active_if('my-reports') ?>">My Reports</a></li>
							<!-- <li><a class="<?= active_if('permissions') ?>" href="<?= base_url('/permissions'); ?>">Add /view Permissons</a></li>
							<li><a class="<?= active_if('modules') ?>" href="<?= base_url('/modules'); ?>">Add /view Modules</a></li> -->
						</ul>
					</li>
					<?php endif; ?>

					<!-- <?php if (hasPermission($permission_mappings, '10', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if('my-courses') ?>">
						<a href="<?= base_url('/my-courses'); ?>" class="dropdown-toggle no-arrow <?= active_if('my-courses') ?>">
							<span class="micon bi bi-house"></span><span class="mtext">MY COURSES</span>
						</a>
					</li>
					<?php endif; ?> -->

					<?php if (hasPermission($permission_mappings, '11', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if('user-test') ?>">
						<a href="<?= base_url('/user-test'); ?>" class="dropdown-toggle no-arrow <?= active_if('user-test') ?>">
							<span class="micon bi bi-house"></span><span class="mtext">My Task</span>
						</a>
					</li>
					<?php endif; ?>

					<!-- <?php if (hasPermission($permission_mappings, '12', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if('my-reports') ?>">
						<a href="<?= base_url('/my-reports'); ?>" class="dropdown-toggle no-arrow <?= active_if('my-reports') ?>">
							<span class="micon bi bi-award-fill"></span><span class="mtext">My Reports</span>
						</a>
					</li>
					<?php endif; ?> -->

					<?php if (hasPermission($permission_mappings, '9', 'CAN_ACCESS')) : ?>
					<li class="dropdown <?= active_if(['users','roles','permissions','modules']) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if(['users','roles','permissions','modules']) ?>">
							<span class="micon bi bi-textarea-resize"></span><span class="mtext">User Management</span>
						</a>
						<ul class="submenu <?= show_if(['users','roles','permissions','modules']) ?>">
							<li><a class="<?= active_if('users') ?>" href="<?= base_url('/users'); ?>">Add / View Users</a></li>
							<li><a class="<?= active_if('roles') ?>" href="<?= base_url('/roles'); ?>">Add /view Roles</a></li>
							<li><a class="<?= active_if('permissions') ?>" href="<?= base_url('/permissions'); ?>">Add /view Permissons</a></li>
							<li><a class="<?= active_if('modules') ?>" href="<?= base_url('/modules'); ?>">Add /view Modules</a></li>
						</ul>
					</li>
					<?php endif; ?>


					<?php if (hasPermission($permission_mappings, '8', 'CAN_ACCESS')) :
						$supportRoutes = ['ticket-list','ticket-department-list','ticket-status-list','ticket-priority-list','welcome-note-text-list'];
					?>
					<li class="dropdown <?= active_if($supportRoutes) ?>">
						<a href="javascript:;" class="dropdown-toggle <?= active_if($supportRoutes) ?>">
							<span class="micon bi bi-gear"></span><span class="mtext">Support</span>
						</a>
						<ul class="submenu <?= show_if($supportRoutes) ?>">
						<li><a class="<?= active_if('admin_media/images') ?>" href="<?= base_url('/admin_media/images'); ?>">Add / View Images</a></li>
							<li><a class="<?= active_if('ticket-list') ?>" href="<?= base_url('/ticket-list'); ?>">Add / view domain config</a></li>
							<li><a class="<?= active_if('ticket-list') ?>" href="<?= base_url('/ticket-list'); ?>">Add / View Tickets</a></li>
							<li>
								<a href="javascript:;" class="dropdown-toggle">Configuration</a>
								<ul class="submenu <?= show_if(['ticket-department-list','ticket-status-list','ticket-priority-list','welcome-note-text-list']) ?>">
									<li><a class="<?= active_if('ticket-department-list') ?>" href="<?= base_url('/ticket-department-list'); ?>">Ticket Department</a></li>
									<li><a class="<?= active_if('ticket-status-list') ?>" href="<?= base_url('/ticket-status-list'); ?>">Ticket Status</a></li>
									<li><a class="<?= active_if('ticket-priority-list') ?>" href="<?= base_url('/ticket-priority-list'); ?>">Ticket Priority</a></li>
									<li><a class="<?= active_if('welcome-note-text-list') ?>" href="<?= base_url('/welcome-note-text-list'); ?>">Welcome Note text</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<?php endif; ?>


					<?php if (hasPermission($permission_mappings, '8', 'CAN_ACCESS')) :
						$supportRoutes = [
							'admin_eforms/templates',
							'admin_eforms/template_create',
							'admin_eforms/form_types',
							'admin_eforms/form_type_create',
							'admin_eforms/requests',
							'admin_eforms/send_form',
							'admin_eforms/send_iict_form',
							'admin_eforms/iict_form_edit',
							'admin_eforms/submissions',
							'admin_eforms/thank_you_templates',
							'admin_eforms/thank_you_template_create',
							'admin_eforms/thank_you_template_edit',
							'eforms/iict_agreement'
						];
					?>
						<li class="dropdown <?= active_if($supportRoutes) ?>">
							<a href="javascript:;" class="dropdown-toggle <?= active_if($supportRoutes) ?>">
								<span class="micon bi bi-gear"></span><span class="mtext">eForms</span>
							</a>

							<ul class="submenu <?= show_if($supportRoutes) ?>">

								<li>
									<a class="<?= active_if('admin_eforms/templates') ?>"
									href="<?= base_url('admin_eforms/templates'); ?>">
										Templates List
									</a>
								</li>

								<li>
									<a class="<?= active_if('admin_eforms/template_create') ?>"
									href="<?= base_url('admin_eforms/template_create'); ?>">
										Add Template
									</a>
								</li>

								<li>
									<a class="<?= active_if('admin_eforms/thank_you_templates') ?>"
									href="<?= base_url('admin_eforms/thank_you_templates'); ?>">
										Thank You Page Templates
									</a>
								</li>

								<li>
									<a class="<?= active_if('admin_eforms/form_types') ?>"
									href="<?= base_url('admin_eforms/form_types'); ?>">
										Form Types
									</a>
								</li>

								<li>
									<a class="<?= active_if('admin_eforms/form_type_create') ?>"
									href="<?= base_url('admin_eforms/form_type_create'); ?>">
										Add Form Type
									</a>
								</li>

								<li>
									<a class="<?= active_if('admin_eforms/requests') ?>"
									href="<?= base_url('admin_eforms/requests'); ?>">
										Sent Requests
									</a>
								</li>
								<li><a class="<?= active_if('admin_eforms/submissions') ?>" href="<?= base_url('admin_eforms/submissions'); ?>">Submitted Forms</a></li>

								<li><a class="<?= active_if('admin_eforms/iict_form_edit') ?>" href="<?= base_url('admin_eforms/iict_form_edit'); ?>">Edit IICT Form</a></li>
								<li><a class="<?= active_if('admin_eforms/send_iict_form') ?>" href="<?= base_url('admin_eforms/send_iict_form'); ?>">Send IICT to Client</a></li>
								<li><a class="<?= active_if('eforms/iict_agreement') ?>" href="<?= base_url('eforms/iict_agreement'); ?>">Fill IICT (My Form)</a></li>

							</ul>
						</li>
					<?php endif; ?>


				

				</ul>
			</div>
		</div>
	</div>

	


	<!-- Client-side fallback: mark active link & open submenu by URL match -->
	<script>
	$(function () {
		var here = window.location.pathname.replace(/\/+$/, '');

		function toPath(href) {
			if (!href) return '';
			try {
				var u = new URL(href, window.location.origin);
				return u.pathname.replace(/\/+$/, '');
			} catch (e) {
				return (href + '').replace(window.location.origin, '').replace(/\/+$/, '');
			}
		}

		// Exact match first
		var matched = false;
		$('#accordion-menu a[href]').each(function () {
			var path = toPath(this.getAttribute('href'));
			if (path && path === here) {
				var $a = $(this).addClass('active');
				$a.closest('ul.submenu').addClass('show').css('display','block');
				$a.closest('li.dropdown').addClass('active');
				matched = true;
			}
		});

		// Fallback: startsWith (useful for detail pages under a route)
		if (!matched) {
			$('#accordion-menu a[href]').each(function () {
				var path = toPath(this.getAttribute('href'));
				if (path && here.indexOf(path) === 0) {
					var $a = $(this).addClass('active');
					$a.closest('ul.submenu').addClass('show').css('display','block');
					$a.closest('li.dropdown').addClass('active');
					matched = true;
				}
			});
		}
	});
	</script>

<script>
(function () {
    function formatTime(date, timeZone) {
        return new Intl.DateTimeFormat('en-US', {
            timeZone: timeZone,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        }).format(date);
    }

    function updateHeaderTimes() {
        var now = new Date();

        var indiaEl = document.getElementById('timeIndia');
        var phEl    = document.getElementById('timePH');
        var melEl   = document.getElementById('timeMel');

        if (indiaEl) indiaEl.textContent = formatTime(now, 'Asia/Kolkata');
        if (phEl)    phEl.textContent    = formatTime(now, 'Asia/Manila');
        if (melEl)   melEl.textContent   = formatTime(now, 'Australia/Melbourne');
    }

    updateHeaderTimes();
    setInterval(updateHeaderTimes, 1000);
})();
</script>

</body>
