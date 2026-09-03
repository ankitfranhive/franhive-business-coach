<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title"><h4><?= html_escape($page_title ?? 'Thank You Page Template'); ?></h4></div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/thank_you_templates'); ?>">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')); ?></div>
            <?php endif; ?>
            <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

            <form method="post" action="<?= html_escape($form_action); ?>" onsubmit="return syncThankYouEditor();">
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Title <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" type="text" name="title" required maxlength="191"
                               value="<?= html_escape(set_value('title', $template['title'] ?? '')); ?>"
                               placeholder="e.g. NLP Registration Thank You">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Active</label>
                    <div class="col-md-10" style="padding-top:8px;">
                        <?php
                            $is_active = set_value('is_active', isset($template['is_active']) ? (string)$template['is_active'] : '1');
                            $checked = ((string)$is_active === '1' || $is_active === 'on');
                        ?>
                        <label>
                            <input type="checkbox" name="is_active" value="1" <?= $checked ? 'checked' : ''; ?>>
                            Show this template in the Send Form dropdown
                        </label>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Page Content <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <textarea class="form-control js-richtext" name="body_html" id="thank_you_body_html" rows="16"><?= html_escape(set_value('body_html', $template['body_html'] ?? '')); ?></textarea>
                        <small class="text-muted">
                            Design the thank you message with the editor. This content replaces the default thank you body
                            (brand header shell stays). Keep content reasonably light (images via URL, not huge embeds).
                        </small>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-10 offset-md-2">
                        <button type="submit" class="btn btn-warning">Save</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>

<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
(function () {
    function initEditors() {
        if (!window.CKEDITOR) return;
        document.querySelectorAll('textarea.js-richtext').forEach(function (ta) {
            if (ta.getAttribute('data-cke-initialized') === '1') return;
            ta.setAttribute('data-cke-initialized', '1');
            CKEDITOR.replace(ta, {
                height: 420,
                allowedContent: true,
                extraAllowedContent: '*(*);*{*}',
                removePlugins: 'exportpdf',
                versionCheck: false
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initEditors);
    } else {
        initEditors();
    }
})();

function syncThankYouEditor() {
    if (window.CKEDITOR && CKEDITOR.instances) {
        for (var name in CKEDITOR.instances) {
            if (Object.prototype.hasOwnProperty.call(CKEDITOR.instances, name)) {
                CKEDITOR.instances[name].updateElement();
            }
        }
    }
    return true;
}
</script>
</html>
