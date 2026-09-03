<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>Payment Agreement Form Settings</h4>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <div class="card-box pd-20 mb-30">
            <form id="payment_agreement_form_settings" method="post" action="<?= base_url('payment-agreement/save-form-settings'); ?>">
                <h5 class="mb-3">Basic</h5>

                <div class="form-group">
                    <label>Form Title</label>
                    <input type="text" name="form_title" class="form-control" value="<?= html_escape($settings['form_title'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Form Subtitle</label>
                    <input type="text" name="form_subtitle" class="form-control" value="<?= html_escape($settings['form_subtitle'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Intro Text</label>
                    <textarea name="intro_text" class="form-control" rows="3"><?= html_escape($settings['intro_text'] ?? ''); ?></textarea>
                </div>

                <h5 class="mb-3 mt-4">Section Headings</h5>

                <div class="form-group">
                    <label>Personal Details</label>
                    <input type="text" name="section_personal_details" class="form-control" value="<?= html_escape($settings['section_personal_details'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Emergency Contact</label>
                    <input type="text" name="section_emergency_contact" class="form-control" value="<?= html_escape($settings['section_emergency_contact'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Course Details</label>
                    <input type="text" name="section_course_details" class="form-control" value="<?= html_escape($settings['section_course_details'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Certification</label>
                    <input type="text" name="section_certification" class="form-control" value="<?= html_escape($settings['section_certification'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Payment arrangement (before certification)</label>
                    <input type="text" name="section_payment_arrangement" class="form-control" value="<?= html_escape($settings['section_payment_arrangement'] ?? ''); ?>" placeholder="Payment arrangement">
                </div>

                <div class="form-group">
                    <label>Payment Details</label>
                    <input type="text" name="section_payment_details" class="form-control" value="<?= html_escape($settings['section_payment_details'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Consent & Terms</label>
                    <input type="text" name="section_terms" class="form-control" value="<?= html_escape($settings['section_terms'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Signature</label>
                    <input type="text" name="section_signature" class="form-control" value="<?= html_escape($settings['section_signature'] ?? ''); ?>">
                </div>

                <h5 class="mb-3 mt-4">Courses on public agreement form</h5>
                <p class="text-muted mb-3" style="font-size:14px;">
                    By default every active course appears under Select Course(s). Enable the option below to show only the courses you tick.
                </p>

                <?php
                    $course_limited = !empty($settings['course_visibility_limited']) && (string)$settings['course_visibility_limited'] === '1';
                    $visible_ids = [];
                    if (!empty($settings['visible_course_ids'])) {
                        $decoded = json_decode($settings['visible_course_ids'], true);
                        if (is_array($decoded)) {
                            $visible_ids = array_map('strval', $decoded);
                        }
                    }
                    $all_courses = isset($all_courses) ? $all_courses : [];
                ?>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="course_visibility_limited" name="course_visibility_limited" value="1" <?= $course_limited ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="course_visibility_limited">Only show selected courses (hide the rest on the client form)</label>
                    </div>
                </div>

                <div id="visible-courses-panel" class="border rounded p-3 mb-3 bg-light" style="<?= $course_limited ? '' : 'display:none;'; ?>">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-2" style="gap:8px;">
                        <strong class="mb-0">Visible courses</strong>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn_select_all_courses">Select all</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btn_clear_all_courses">Clear</button>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                            $listed = 0;
                            foreach ($all_courses as $course):
                                if (isset($course['RECORD_STATUS']) && (int)$course['RECORD_STATUS'] !== 0) {
                                    continue;
                                }
                                $course_id = isset($course['COURSE_ID']) ? $course['COURSE_ID'] : '';
                                $course_label = isset($course['NAME']) ? trim($course['NAME']) : '';
                                if ($course_id === '' || $course_label === '') {
                                    continue;
                                }
                                $listed++;
                                $cid_str = (string)$course_id;
                                $is_on = in_array($cid_str, $visible_ids, true);
                        ?>
                            <div class="col-md-6 mb-2">
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input visible-course-cb"
                                        id="vis_course_<?= html_escape($course_id); ?>"
                                        name="visible_course_ids[]"
                                        value="<?= html_escape($course_id); ?>"
                                        <?= $is_on ? 'checked' : ''; ?>
                                    >
                                    <label class="custom-control-label" for="vis_course_<?= html_escape($course_id); ?>"><?= html_escape($course_label); ?></label>
                                </div>
                            </div>
                        <?php
                            endforeach;
                            if ($listed === 0):
                        ?>
                            <div class="col-12 text-muted">No active courses found in the catalog.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <script>
                (function () {
                    var toggle = document.getElementById('course_visibility_limited');
                    var panel = document.getElementById('visible-courses-panel');
                    if (toggle && panel) {
                        toggle.addEventListener('change', function () {
                            panel.style.display = this.checked ? 'block' : 'none';
                        });
                    }
                    var cbs = function () { return document.querySelectorAll('.visible-course-cb'); };
                    var btnAll = document.getElementById('btn_select_all_courses');
                    var btnClr = document.getElementById('btn_clear_all_courses');
                    if (btnAll) {
                        btnAll.addEventListener('click', function () {
                            cbs().forEach(function (el) { el.checked = true; });
                        });
                    }
                    if (btnClr) {
                        btnClr.addEventListener('click', function () {
                            cbs().forEach(function (el) { el.checked = false; });
                        });
                    }
                })();
                </script>

                <h5 class="mb-3 mt-4">How we deliver our services (public form)</h5>
                <p class="text-muted mb-3" style="font-size:14px;">
                    Shown on the agreement form instead of a location dropdown. Leave blank to use the default wording below.
                </p>
                <div class="form-group">
                    <label>Heading</label>
                    <input type="text" name="delivery_services_heading" class="form-control" value="<?= html_escape($settings['delivery_services_heading'] ?? ''); ?>" placeholder="How we deliver our services">
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea name="delivery_services_text" class="form-control" rows="6" placeholder="Default explains Zoom, hybrid options, and registration preferences."><?= html_escape($settings['delivery_services_text'] ?? ''); ?></textarea>
                </div>

                <h5 class="mb-3 mt-4">Payment arrangement (public form)</h5>
                <p class="text-muted mb-3" style="font-size:14px;">
                    Shown after course / financial fields and before Certification. HTML is allowed. Use form fields on the public page for pay-in-full vs payment plan; this area customises intro, bonus, and footer text. Optional hard end-date caps commitment dates together with course dates.
                </p>
                <div class="form-group">
                    <label>Intro (HTML)</label>
                    <textarea name="payment_arrangement_intro_html" class="form-control" rows="5" placeholder="Optional. Default text is used if empty."><?= htmlspecialchars($settings['payment_arrangement_intro_html'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Bonus paragraph (HTML, Pay in Full block)</label>
                    <textarea name="payment_arrangement_bonus_html" class="form-control" rows="3"><?= htmlspecialchars($settings['payment_arrangement_bonus_html'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Footer note (HTML, Payment Plan block)</label>
                    <textarea name="payment_arrangement_plan_footer_html" class="form-control" rows="3"><?= htmlspecialchars($settings['payment_arrangement_plan_footer_html'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Optional latest allowed date (YYYY-MM-DD)</label>
                    <input type="date" name="payment_arrangement_date_max_override" class="form-control" value="<?= html_escape($settings['payment_arrangement_date_max_override'] ?? ''); ?>">
                    <small class="text-muted">If set, commitment dates cannot be after this date <em>or</em> after the latest course date (whichever is earlier). Ignored for the next option.</small>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="payment_arrangement_allow_dates_after_training" name="payment_arrangement_allow_dates_after_training" value="1" <?= !empty($settings['payment_arrangement_allow_dates_after_training']) && (string)$settings['payment_arrangement_allow_dates_after_training'] === '1' ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="payment_arrangement_allow_dates_after_training">Allow dates after training (use only optional admin date above, not course dates)</label>
                    </div>
                </div>

                <h5 class="mb-3 mt-4">Payment modes (public form)</h5>
                <p class="text-muted mb-3" style="font-size:14px;">
                    Replaces card and Stripe ID fields. Clients still choose one option: Xero/Stripe or Bank/PayID. Leave blank to use the default wording.
                </p>
                <div class="form-group">
                    <label>Heading</label>
                    <input type="text" name="payment_modes_heading" class="form-control" value="<?= html_escape($settings['payment_modes_heading'] ?? ''); ?>" placeholder="Mode of Payment:">
                </div>
                <div class="form-group">
                    <label>Body</label>
                    <textarea name="payment_modes_body" class="form-control" rows="14" placeholder="Xero, Stripe, bank details, PayID…"><?= html_escape($settings['payment_modes_body'] ?? ''); ?></textarea>
                </div>

                <h5 class="mb-3 mt-4">Terms</h5>

                <div class="form-group">
                    <label>Term 1</label>
                    <textarea name="term_1" class="form-control" rows="2"><?= html_escape($settings['term_1'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Term 2</label>
                    <textarea name="term_2" class="form-control" rows="2"><?= html_escape($settings['term_2'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Term 3</label>
                    <textarea name="term_3" class="form-control" rows="2"><?= html_escape($settings['term_3'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Term 4</label>
                    <textarea name="term_4" class="form-control" rows="2"><?= html_escape($settings['term_4'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Term 5</label>
                    <textarea name="term_5" class="form-control" rows="2"><?= html_escape($settings['term_5'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <label>Term 6</label>
                    <textarea name="term_6" class="form-control" rows="2"><?= html_escape($settings['term_6'] ?? ''); ?></textarea>
                </div>

                <h5 class="mb-3 mt-4">Additional consent text (rich)</h5>
                <p class="text-muted mb-3" style="font-size:14px;">
                    Shown on the public form after the &ldquo;Consent &amp; Terms&rdquo; heading (when that heading is shown). Use the editor for bold, lists, and links. If every Term 1&ndash;6 field above is cleared and saved empty, the main consent heading is hidden; this block can still appear using the optional heading below.
                </p>
                <div class="form-group">
                    <label>Optional heading (e.g. Further terms)</label>
                    <input type="text" name="consent_rich_heading" class="form-control" value="<?= html_escape($settings['consent_rich_heading'] ?? ''); ?>" placeholder="Optional title above the rich text">
                </div>
                <div class="form-group">
                    <label>Rich text</label>
                    <textarea id="consent_rich_html_editor" name="consent_rich_html" class="form-control" rows="12"><?= htmlspecialchars($settings['consent_rich_html'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
(function () {
    var form = document.getElementById('payment_agreement_form_settings');
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#consent_rich_html_editor',
            height: 320,
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link removeformat | code',
            branding: false,
            convert_urls: false
        });
    }
    if (form) {
        form.addEventListener('submit', function () {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    }
})();
</script>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>