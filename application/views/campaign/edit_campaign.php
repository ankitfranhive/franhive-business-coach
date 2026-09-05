<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
   <div class="pd-ltr-20 xs-pd-20-10">
      <div class="page-header">
         <div class="row">
            <div class="col-md-6 col-sm-12">
               <div class="title">
                  <h4>Update Campaign Details: [<?= $campaign_data['TITLE'] ?>]</h4>
               </div>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
               <a class="btn btn-warning" href="<?= base_url('/campaigns'); ?>" role="button">
                  Back To Campaign List
               </a>
            </div>
         </div>
      </div>
      <div class="pd-20 card-box mb-30">
         <div class="clearfix">
            <div class="pull-left">
               <!-- <h4 class="text-blue h4">Update Campaign</h4> -->
            </div>
         </div>
         <form id="campaignForm" action="<?= base_url('CampaignController/updateCampaign') ?>" method="post">
            <input type="hidden" name="CAMPAIGN_ID" value="<?= $campaign_data['CAMPAIGN_ID'] ?>">
            <input type="hidden" name="SAVE_ACTION" id="SAVE_ACTION" value="setup">

            <!-- Screen 1 -->
            <div id="screen1" class="screen">
               <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">Name this campaign</label>
                  <div class="col-sm-12 col-md-10">
                     <input class="form-control" type="text" name="TITLE" placeholder="Campaign title" value="<?= $campaign_data['TITLE'] ?>" />
                     <i>
                        <p>The campaign name is shown in your reports and your email archive.</p>
                     </i>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">Who is it from?</label>
                  <div class="col-sm-12 col-md-10">
                     <div class="row">
                        <div class="col-md-6">
                           <input class="form-control" name="MANAGER_NAME" type="text" placeholder="Name" value="<?= $campaign_data['MANAGER_NAME'] ?>" />
                           <i>
                              <p>This will display in the From field.</p>
                           </i>
                        </div>
                        <div class="col-md-6">
                           <input class="form-control" value="NLP@empoweryourdestiny.com.au" name="REPLY_ADDRESS" type="text" placeholder="Name" disabled />
                        </div>
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">Create campaign for</label>
                  <div class="col-sm-12 col-md-10">
                     <select class="custom-select col-12" name="MODULE_NAME" id="audienceSelect">
                        <option value="">Choose...</option>
                        <option value="Lead" <?= $campaign_data['MODULE_NAME'] == 'Lead' ? 'selected' : '' ?>>Leads</option>
                        <option value="Client" <?= $campaign_data['MODULE_NAME'] == 'Client' ? 'selected' : '' ?>>Clients</option>
                        <option value="User" <?= $campaign_data['MODULE_NAME'] == 'User' ? 'selected' : '' ?>>Select from users</option>
                     </select>
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">Workflow status</label>
                  <div class="col-sm-12 col-md-10">
                     <?php $wf = $campaign_data['WORKFLOW_STATUS'] ?? 'draft'; $meta = campaign_status_meta($wf); ?>
                     <input type="hidden" name="WORKFLOW_STATUS" value="<?= htmlspecialchars($wf) ?>">
                     <span class="badge <?= $meta['class'] ?>"><?= $meta['label'] ?></span>
                     <small class="text-muted d-block"><?= $meta['help'] ?>. Use the buttons on the last step to change this.</small>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-sm-6 col-md-4">
                     <button type="button" class="btn btn-warning" onclick="nextScreen()">Next</button>
                  </div>
               </div>
            </div>

            <!-- Screen 2 -->
            <div id="screen2" class="screen" style="display: none;">
               <h4>Select Templates</h4>
               <?php $campaign_timezone = campaign_normalize_timezone($campaign_data['TIMEZONE'] ?? campaign_default_timezone()); ?>
               <?php $this->load->view('campaign/partials/campaign_timezone_bar', ['campaign_timezone' => $campaign_timezone]); ?>
               <?php $this->load->view('campaign/partials/campaign_templates_toolbar'); ?>
               <div id="templatesTableWrap" class="pb-20 mt-2">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th><input type="checkbox" class="template-select-page" title="Select this page"></th>
                           <th>Template Name</th>
                           <th>Subject</th>
                           <th>Order</th>
                           <th>Send Date</th>
                           <th>Time</th>
                           <th>Timezone</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($all_templates as $template): ?>
                           <?php $selected = $selected_templates[$template['TEMPLATE_ID']] ?? null; ?>
                           <tr>
                              <td>
                                 <input type="checkbox" name="TEMPLATE_IDS[]" value="<?= $template['TEMPLATE_ID'] ?>"
                                    <?= $selected ? 'checked' : '' ?>>
                              </td>
                              <td data-search="<?= htmlspecialchars($template['TEMPLATE_NAME']) ?>"><?= htmlspecialchars($template['TEMPLATE_NAME']) ?></td>
                              <td data-search="<?= htmlspecialchars($template['TEMPLATE_SUBJECT']) ?>"><?= htmlspecialchars($template['TEMPLATE_SUBJECT']) ?></td>
                              <td>
                                 <input type="number" placeholder="Order" name="SENDING_ORDER[<?= $template['TEMPLATE_ID'] ?>]" min="1" value="<?= $selected['SENDING_ORDER'] ?? '' ?>" class="form-control">
                              </td>
                              <td>
                                 <input type="date" name="SEND_DATE[<?= $template['TEMPLATE_ID'] ?>]" value="<?= $selected['SEND_DATE'] ?? '' ?>" class="form-control">
                              </td>
                              <td>
                                 <input type="time" name="TEMPLATE_START_TIME[<?= $template['TEMPLATE_ID'] ?>]" value="<?= $selected ? campaign_local_time_value($selected) : '' ?>" class="form-control">
                              </td>
                              <td>
                                 <?= campaign_timezone_select(
                                     'TEMPLATE_TIMEZONE[' . $template['TEMPLATE_ID'] . ']',
                                     $selected['TIMEZONE'] ?? $campaign_timezone
                                 ) ?>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
               <button type="button" class="btn btn-warning" onclick="prevScreen()">Back</button>
               <button type="button" class="btn btn-warning" onclick="nextScreen()">Next</button>
            </div>




            <!-- Screen 3 -->
            <div id="screen3" class="screen" style="display: none;">
               <h4>Select Contacts</h4>
               <p class="text-muted" id="recipientsHint">Each selected person gets the emails, with merge tags filled from their record.</p>
               <?php
               $all_users = isset($all_users) && is_array($all_users) ? $all_users : [];
               $crm_users = isset($crm_users) && is_array($crm_users) ? $crm_users : [];
               $selected_contact_ids = isset($selected_contact_ids) && is_array($selected_contact_ids) ? $selected_contact_ids : [];
               $is_user_audience = ($campaign_data['MODULE_NAME'] ?? '') === 'User';
               ?>
               <?php $this->load->view('campaign/partials/campaign_contacts_toolbar'); ?>
               <div id="leadsContactsWrap" class="audience-wrap pb-20">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th><input type="checkbox" class="contact-select-page" title="Select this page"></th>
                           <th>Name</th>
                           <th>Email</th>
                           <th>Phone</th>
                           <th>Type</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($all_users as $user): ?>
                           <?php if (($user['IS_LEAD'] ?? '') !== 'Y') continue; ?>
                           <tr>
                              <td>
                                 <input type="checkbox" name="CONTACT_IDS[]" value="<?= (int)$user['ENTITY_ID'] ?>"
                                    <?= !$is_user_audience && in_array($user['ENTITY_ID'], $selected_contact_ids) ? 'checked' : '' ?> disabled>
                              </td>
                              <td><?= htmlspecialchars($user['NAME']) ?></td>
                              <td><?= htmlspecialchars($user['EMAIL']) ?></td>
                              <td><?= htmlspecialchars($user['MOBILE']) ?></td>
                              <td>Lead</td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
               <div id="clientsContactsWrap" class="audience-wrap pb-20" style="display:none;">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th><input type="checkbox" class="contact-select-page" title="Select this page"></th>
                           <th>Name</th>
                           <th>Email</th>
                           <th>Phone</th>
                           <th>Type</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($all_users as $user): ?>
                           <?php if (($user['IS_LEAD'] ?? '') === 'Y') continue; ?>
                           <tr>
                              <td>
                                 <input type="checkbox" name="CONTACT_IDS[]" value="<?= (int)$user['ENTITY_ID'] ?>"
                                    <?= !$is_user_audience && in_array($user['ENTITY_ID'], $selected_contact_ids) ? 'checked' : '' ?> disabled>
                              </td>
                              <td><?= htmlspecialchars($user['NAME']) ?></td>
                              <td><?= htmlspecialchars($user['EMAIL']) ?></td>
                              <td><?= htmlspecialchars($user['MOBILE']) ?></td>
                              <td>Client</td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
               <div id="crmUsersWrap" class="audience-wrap pb-20" style="display:none;">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th><input type="checkbox" class="contact-select-page" title="Select this page"></th>
                           <th>Name</th>
                           <th>Email</th>
                           <th>Phone</th>
                           <th>Type</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($crm_users as $crm_user): ?>
                           <tr>
                              <td>
                                 <input type="checkbox" name="CONTACT_IDS[]" value="<?= (int)$crm_user['USER_ID'] ?>"
                                    <?= $is_user_audience && in_array($crm_user['USER_ID'], $selected_contact_ids) ? 'checked' : '' ?> disabled>
                              </td>
                              <td><?= htmlspecialchars($crm_user['NAME']) ?></td>
                              <td><?= htmlspecialchars($crm_user['EMAIL']) ?></td>
                              <td><?= htmlspecialchars($crm_user['MOBILE'] ?? '') ?></td>
                              <td>CRM user</td>
                           </tr>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
               <button type="button" class="btn btn-warning" onclick="prevScreen()">Back</button>
               <button type="button" class="btn btn-warning" onclick="nextScreen()">Next</button>
            </div>


            <!-- Screen 4 -->
            <div id="screen4" class="screen" style="display: none;">
               <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">Start Date</label>
                  <div class="col-sm-12 col-md-10">
                     <input class="form-control" name="START_DATE" placeholder="Choose Date and time" type="date" value="<?= $campaign_data['START_DATE'] ?>">
                  </div>
               </div>
               <div class="form-group row">
                  <label class="col-sm-12 col-md-2 col-form-label">End Date</label>
                  <div class="col-sm-12 col-md-10">
                     <input class="form-control" name="END_DATE" placeholder="Choose Date and time" type="date" value="<?= $campaign_data['END_DATE'] ?>">
                  </div>
               </div>
               <button type="button" class="btn btn-warning" onclick="prevScreen()">Back</button>
               <button type="button" class="btn btn-secondary" onclick="submitCampaign('draft')">Save draft</button>
               <button type="button" class="btn btn-info" onclick="submitCampaign('setup')">Mark setup done</button>
               <button type="button" class="btn btn-success" onclick="submitCampaign('scheduled')">Trigger campaign</button>
            </div>
         </form>
      </div>
   </div>
</div>
<?php $this->load->view('includes/footer'); ?>
<script>
   var currentScreen = 1;

   function currentAudience() {
      var sel = document.getElementById('audienceSelect');
      return sel ? sel.value : '';
   }

   function setAudienceTables() {
      var audience = currentAudience();
      var wraps = {
         Lead: document.getElementById('leadsContactsWrap'),
         Client: document.getElementById('clientsContactsWrap'),
         User: document.getElementById('crmUsersWrap')
      };
      var hints = {
         Lead: 'Showing all leads. Select one or more.',
         Client: 'Showing all clients. Select one or more.',
         User: 'Showing all CRM users. Select one or more.'
      };
      var hint = document.getElementById('recipientsHint');
      if (hint) {
         hint.textContent = hints[audience] || 'Each selected person gets the emails, with merge tags filled from their record.';
      }
      Object.keys(wraps).forEach(function (key) {
         var wrap = wraps[key];
         if (!wrap) return;
         var active = key === audience;
         wrap.style.display = active ? 'block' : 'none';
         wrap.querySelectorAll('input[name="CONTACT_IDS[]"]').forEach(function (cb) {
            cb.disabled = !active;
            if (!active) cb.checked = false;
         });
      });
   }

   function nextScreen() {
      if (currentScreen === 1 && !currentAudience()) {
         alert('Please choose an audience first.');
         return;
      }
      if (currentScreen < 4) {
         document.getElementById('screen' + currentScreen).style.display = 'none';
         currentScreen++;
         document.getElementById('screen' + currentScreen).style.display = 'block';
         if (currentScreen === 3) setAudienceTables();
         updateProgressBar();
      }
   }

   function prevScreen() {
      if (currentScreen > 1) {
         document.getElementById('screen' + currentScreen).style.display = 'none';
         currentScreen--;
         document.getElementById('screen' + currentScreen).style.display = 'block';
         updateProgressBar();
      }
   }

   function submitCampaign(action) {
      document.getElementById('SAVE_ACTION').value = action;
      document.getElementById('campaignForm').submit();
   }
   function submitForm() {
      submitCampaign('setup');
   }

   function updateProgressBar() {
      var progressBar = document.getElementById('progressBar');
      if (progressBar) {
         progressBar.style.width = ((currentScreen - 1) * 33.33) + '%';
      }

      var dots = document.querySelectorAll('.dot');
      dots.forEach((dot, index) => {
         if (index === currentScreen - 1) {
            dot.classList.add('active');
         } else {
            dot.classList.remove('active');
         }
      });
   }

   document.addEventListener('DOMContentLoaded', function () {
      var sel = document.getElementById('audienceSelect');
      if (sel) sel.addEventListener('change', setAudienceTables);
      setAudienceTables();
   });
</script>

</body>
<script src="src/plugins/datatables/js/jquery.dataTables.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/dataTables.responsive.min.js"></script>
<script src="src/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
<!-- buttons for Export datatable -->
<script src="src/plugins/datatables/js/dataTables.buttons.min.js"></script>
<script src="src/plugins/datatables/js/buttons.bootstrap4.min.js"></script>
<script src="src/plugins/datatables/js/buttons.print.min.js"></script>
<script src="src/plugins/datatables/js/buttons.html5.min.js"></script>
<script src="src/plugins/datatables/js/buttons.flash.min.js"></script>
<script src="src/plugins/datatables/js/pdfmake.min.js"></script>
<script src="src/plugins/datatables/js/vfs_fonts.js"></script>
<!-- Datatable Setting js -->
<script src="vendors/scripts/datatable-setting.js"></script>
</html>