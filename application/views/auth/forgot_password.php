<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Forgot Password</title>

  <!-- Prevent caching so stale flash messages don't reappear on reload/back -->
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
  <meta http-equiv="Cache-Control" content="post-check=0, pre-check=0">
  <meta http-equiv="Pragma" content="no-cache">
  <meta http-equiv="Expires" content="0">

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
            <h3 class="text-center mb-3">Forgot your password?</h3>
            <p class="text-center">Enter your email to receive a password reset link.</p>

            <?php
              $error   = $this->session->flashdata('error');
              $success = $this->session->flashdata('success');
            ?>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger js-flash"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
              <div class="alert alert-success js-flash"><?php echo $success; ?></div>
            <?php endif; ?>

            <form action="<?php echo site_url('forgot-password/submit'); ?>" method="post" novalidate>
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                     value="<?php echo $this->security->get_csrf_hash(); ?>">

              <div class="form-group">
                <label for="email">Email address</label>
                <input class="form-control form-control-lg"
                       type="email"
                       id="email"
                       name="email"
                       required
                       autocomplete="email"
                       autofocus>
              </div>

              <div class="mt-3">
                <button class="btn btn-primary btn-block btn-lg" type="submit">Send reset link</button>
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

  <!-- If the page is served from bfcache (back/refresh), remove any stale flash alerts -->
  <script>
    window.addEventListener('pageshow', function (e) {
      if (e.persisted) {
        var flashes = document.querySelectorAll('.js-flash');
        flashes.forEach(function(el){ el.parentNode && el.parentNode.removeChild(el); });
      }
    });
  </script>
</body>
</html>
