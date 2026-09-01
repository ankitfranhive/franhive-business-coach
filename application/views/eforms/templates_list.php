<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<?php if ($this->session->flashdata('success')): ?>
  <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php endif; ?>
<?php if ($this->session->flashdata('error')): ?>
  <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>eForms Templates</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/template_create'); ?>" role="button">
                        Add Template
                    </a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <h4 class="text-blue h4">Templates List</h4>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width:80px;">ID</th>
                            <th>Template Title</th>
                            <th>Form Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($templates)) : ?>
                            <?php foreach ($templates as $t) : ?>
                                <tr>
                                    <td><?= (int)$t['id']; ?></td>
                                    <td><?= html_escape($t['title']); ?></td>
                                    <td><?= html_escape($t['form_type_name'] ?? '-'); ?></td>
                                    <td>
                                        <a class="btn btn-sm btn-primary"
                                        href="<?= base_url('admin_eforms/send_form/' . $t['id']); ?>">
                                            Send
                                        </a>

                                        <a class="btn btn-sm btn-info"
                                        href="<?= base_url('admin_eforms/template_edit/' . $t['id']); ?>">
                                            Edit
                                        </a>

                                        <button type="button"
                                            class="btn btn-sm btn-secondary js-copy-form-link"
                                            data-link="<?= html_escape($t['public_link'] ?? base_url('eforms/open/' . (int)$t['id'])); ?>"
                                            title="<?= html_escape($t['public_link'] ?? ''); ?>">
                                            Copy Link
                                        </button>

                                        <a class="btn btn-sm btn-danger"
                                        href="<?= base_url('admin_eforms/template_delete/' . $t['id']); ?>"
                                        onclick="return confirm('Are you sure you want to delete this template?');">
                                            Delete
                                        </a>
                                    </td>

                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">No templates found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
<script>
(function () {
  function copyText(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
      return navigator.clipboard.writeText(text);
    }
    return new Promise(function (resolve, reject) {
      var input = document.createElement('input');
      input.value = text;
      document.body.appendChild(input);
      input.select();
      try {
        document.execCommand('copy');
        resolve();
      } catch (err) {
        reject(err);
      } finally {
        document.body.removeChild(input);
      }
    });
  }

  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.js-copy-form-link');
    if (!btn) return;

    var link = btn.getAttribute('data-link') || '';
    if (!link) return;

    var original = btn.textContent;
    copyText(link).then(function () {
      btn.textContent = 'Copied!';
      setTimeout(function () { btn.textContent = original; }, 1500);
    }).catch(function () {
      window.prompt('Copy this form link:', link);
    });
  });
})();
</script>
</html>
