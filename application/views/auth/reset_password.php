<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Set New Password</title>
  <link rel="stylesheet" href="<?php echo base_url('vendors/styles/core.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('vendors/styles/style.css'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="login-page">
  <div class="hero-overlay">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
          <div class="login-box">

            <h3 class="text-center mb-3">Create a new password</h3>

            <?php if($this->session->flashdata('error')): ?>
              <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if($this->session->flashdata('success')): ?>
              <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
            <?php endif; ?>

            <form action="<?php echo site_url('auth/reset_password_submit'); ?>" method="post" novalidate>
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                     value="<?php echo $this->security->get_csrf_hash(); ?>">
              <input type="hidden" name="token" value="<?php echo html_escape($token); ?>">

              <div class="form-group">
                <label for="password">New password</label>
                <input class="form-control form-control-lg" type="password" id="password" name="password" minlength="8" required autocomplete="new-password">
              </div>

              <div class="form-group">
                <label for="password_confirm">Confirm new password</label>
                <input class="form-control form-control-lg" type="password" id="password_confirm" name="password_confirm" minlength="8" required autocomplete="new-password">
              </div>

              <div class="mt-3">
                <button class="btn btn-primary btn-block btn-lg" type="submit">Update password</button>
              </div>
              <div class="mt-2 text-center">
                <a href="<?php echo site_url('login'); ?>">Back to login</a>
              </div>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
