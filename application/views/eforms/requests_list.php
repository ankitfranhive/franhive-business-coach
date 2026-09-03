<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">

    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title"><h4>Sent Requests</h4></div>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Template</th>
              <th>Client</th>
              <th>Email</th>
              <th>Status</th>
              <th>Expires</th>
              <th>Link</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($requests as $r): ?>
            <tr>
              <td><?= (int)$r['id'] ?></td>
              <td><?= html_escape($r['template_title']) ?></td>
              <td><?= html_escape($r['client_name']) ?></td>
              <td><?= html_escape($r['client_email']) ?></td>
              <td><?= html_escape($r['status']) ?></td>
              <td><?= html_escape($r['expires_at']) ?></td>
              <td>
                <a target="_blank" href="<?= base_url('eforms/fill/'.$r['token']); ?>">Open</a>
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
