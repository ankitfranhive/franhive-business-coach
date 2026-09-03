<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">

    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title"><h4>Form Types</h4></div>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('admin_eforms/form_type_create'); ?>">Add Form Type</a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Slug</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($form_types as $ft): ?>
            <tr>
              <td><?= (int)$ft['id'] ?></td>
              <td><?= html_escape($ft['name']) ?></td>
              <td><?= html_escape($ft['slug']) ?></td>
              <td>
                <a class="btn btn-sm btn-primary" href="<?= base_url('admin_eforms/form_type_edit/'.$ft['id']); ?>">Edit</a>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>
