<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('includes/header'); ?>
<?php $this->load->view('campaign/partials/campaign_styles'); ?>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6">
                    <h4>New campaign</h4>
                    <p class="text-muted mb-0">Name it, choose emails, pick one or more contacts, then save or trigger.</p>
                </div>
                <div class="col-md-6 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/campaigns'); ?>">Back to campaigns</a>
                </div>
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="cm-step">
                <span class="step-label active" data-step="1">1. Setup</span>
                <span class="step-label" data-step="2">2. Emails</span>
                <span class="step-label" data-step="3">3. Recipients</span>
                <span class="step-label" data-step="4">4. Schedule</span>
            </div>
            <form id="campaignForm" action="<?= base_url('CampaignController/create_campaign') ?>" method="post">
                <input type="hidden" name="SAVE_ACTION" id="SAVE_ACTION" value="draft">

                <div id="screen1" class="screen">
                    <div class="form-group">
                        <label>Campaign name</label>
                        <input class="form-control" type="text" name="TITLE" placeholder="e.g. September nurture sequence" required>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>From name</label>
                            <input class="form-control" name="MANAGER_NAME" type="text" placeholder="EYD Team">
                        </div>
                        <div class="col-md-6">
                            <label>Reply address</label>
                            <input class="form-control" value="nlp@empoweryourdestiny.com.au" name="REPLY_ADDRESS" type="text" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Audience</label>
                        <select class="custom-select" name="MODULE_NAME" id="audienceSelect" required>
                            <option value="">Choose...</option>
                            <option value="Lead">Leads</option>
                            <option value="Client">Clients</option>
                            <option value="User">Select from users</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="nextScreen()">Next: emails</button>
                </div>

                <div id="screen2" class="screen" style="display:none;">
                    <h5>Select one or more email templates</h5>
                    <p class="text-muted">Only campaign templates are listed. Payment Agreement / eForm templates stay in their own modules.</p>
                    <?php $campaign_timezone = campaign_default_timezone(); ?>
                    <?php $this->load->view('campaign/partials/campaign_timezone_bar', ['campaign_timezone' => $campaign_timezone]); ?>
                    <?php $this->load->view('campaign/partials/campaign_templates_toolbar'); ?>
                    <div id="templatesTableWrap" class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" class="template-select-page" title="Select this page"></th>
                                    <th>Template</th>
                                    <th>Subject</th>
                                    <th>Order</th>
                                    <th>Send date</th>
                                    <th>Time</th>
                                    <th>Timezone</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($all_templates as $template): ?>
                                <tr>
                                    <td><input type="checkbox" name="TEMPLATE_IDS[]" value="<?= $template['TEMPLATE_ID'] ?>"></td>
                                    <td data-search="<?= htmlspecialchars($template['TEMPLATE_NAME']) ?>"><?= htmlspecialchars($template['TEMPLATE_NAME']) ?></td>
                                    <td data-search="<?= htmlspecialchars($template['TEMPLATE_SUBJECT']) ?>"><?= htmlspecialchars($template['TEMPLATE_SUBJECT']) ?></td>
                                    <td><input type="number" name="SENDING_ORDER[<?= $template['TEMPLATE_ID'] ?>]" min="1" class="form-control"></td>
                                    <td><input type="date" name="SEND_DATE[<?= $template['TEMPLATE_ID'] ?>]" class="form-control"></td>
                                    <td><input type="time" name="TEMPLATE_START_TIME[<?= $template['TEMPLATE_ID'] ?>]" class="form-control"></td>
                                    <td><?= campaign_timezone_select('TEMPLATE_TIMEZONE[' . $template['TEMPLATE_ID'] . ']', $campaign_timezone) ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <a class="btn btn-outline-secondary" href="<?= base_url('/add-template'); ?>">Create a template</a>
                    <button type="button" class="btn btn-light" onclick="prevScreen()">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextScreen()">Next: recipients</button>
                </div>

                <div id="screen3" class="screen" style="display:none;">
                    <h5>Choose one or many contacts</h5>
                    <p class="text-muted" id="recipientsHint">Each selected person gets the emails, with merge tags filled from their record.</p>
                    <?php
                    $all_users = isset($all_users) && is_array($all_users) ? $all_users : [];
                    $crm_users = isset($crm_users) && is_array($crm_users) ? $crm_users : [];
                    ?>
                    <?php $this->load->view('campaign/partials/campaign_contacts_toolbar'); ?>
                    <div id="leadsContactsWrap" class="audience-wrap table-responsive">
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
                                    <td><input type="checkbox" name="CONTACT_IDS[]" value="<?= (int)$user['ENTITY_ID'] ?>" disabled></td>
                                    <td><?= htmlspecialchars($user['NAME']) ?></td>
                                    <td><?= htmlspecialchars($user['EMAIL']) ?></td>
                                    <td><?= htmlspecialchars($user['MOBILE']) ?></td>
                                    <td>Lead</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="clientsContactsWrap" class="audience-wrap table-responsive" style="display:none;">
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
                                    <td><input type="checkbox" name="CONTACT_IDS[]" value="<?= (int)$user['ENTITY_ID'] ?>" disabled></td>
                                    <td><?= htmlspecialchars($user['NAME']) ?></td>
                                    <td><?= htmlspecialchars($user['EMAIL']) ?></td>
                                    <td><?= htmlspecialchars($user['MOBILE']) ?></td>
                                    <td>Client</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="crmUsersWrap" class="audience-wrap table-responsive" style="display:none;">
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
                                    <td><input type="checkbox" name="CONTACT_IDS[]" value="<?= (int)$crm_user['USER_ID'] ?>" disabled></td>
                                    <td><?= htmlspecialchars($crm_user['NAME']) ?></td>
                                    <td><?= htmlspecialchars($crm_user['EMAIL']) ?></td>
                                    <td><?= htmlspecialchars($crm_user['MOBILE'] ?? '') ?></td>
                                    <td>CRM user</td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-light" onclick="prevScreen()">Back</button>
                    <button type="button" class="btn btn-primary" onclick="nextScreen()">Next: schedule</button>
                </div>

                <div id="screen4" class="screen" style="display:none;">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label>Campaign start date</label>
                            <input class="form-control" name="START_DATE" type="date">
                        </div>
                        <div class="col-md-6">
                            <label>Campaign end date</label>
                            <input class="form-control" name="END_DATE" type="date">
                        </div>
                    </div>
                    <p class="text-muted">Draft = keep working. Setup done = emails and contacts attached. Trigger = cron will send at the times you set.</p>
                    <button type="button" class="btn btn-light" onclick="prevScreen()">Back</button>
                    <button type="button" class="btn btn-secondary" onclick="submitCampaign('draft')">Save as draft</button>
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
function setStep(n) {
    document.querySelectorAll('.step-label').forEach(function (el) {
        el.classList.toggle('active', parseInt(el.getAttribute('data-step'), 10) === n);
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
        setStep(currentScreen);
        if (currentScreen === 3) setAudienceTables();
    }
}
function prevScreen() {
    if (currentScreen > 1) {
        document.getElementById('screen' + currentScreen).style.display = 'none';
        currentScreen--;
        document.getElementById('screen' + currentScreen).style.display = 'block';
        setStep(currentScreen);
    }
}
function submitCampaign(action) {
    document.getElementById('SAVE_ACTION').value = action;
    document.getElementById('campaignForm').submit();
}
document.addEventListener('DOMContentLoaded', function () {
    var sel = document.getElementById('audienceSelect');
    if (sel) sel.addEventListener('change', setAudienceTables);
    setAudienceTables();
});
</script>
</html>
