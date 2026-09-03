<!DOCTYPE html>
<html lang="en">
<?php
  // ---------- Multi-tenant config ----------
  $host      = $_SERVER['HTTP_HOST'] ?? '';
  $parts     = explode('.', $host);
  // subdomain = first part for sub.domain.tld style hosts
  $subdomain = (count($parts) >= 3) ? $parts[0] : '';

  // Tenants (add more as needed)
  $tenants = [
    'eyd' => [
      'name'      => 'Empower Your Destiny',
      'logo'      => 'https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png',
      'banner'    => 'https://eyd.franhive.com/vendors/images/login_page_banner_eyd.png',
      'home_url'  => 'https://empoweryourdestiny.com.au/',
      'login_cta' => 'Login to Empower Your Destiny',
      'social'    => [
        'whatsapp'           => 'https://wa.me/61426886501?text=Hi%20Barinderjeet%2C%20I%27m%20interested%20in%20the%20Destiny%20Duo%20Certification.',
        'messenger'          => 'https://m.me/empoweryourdestiny',
        'messenger_personal' => 'https://m.me/barinderjeet.kaur.39',
        // 'facebook' => '',
        // 'instagram'=> '',
        // 'youtube'  => '',
        // 'linkedin' => '',
      ],
      'support_text' => 'Email us at info@empoweryourdestiny.com.au for further assistance and support',
      'social_hint'  => 'Not yet a member? Get in touch and start your transformation journey…',
    ],
  ];

  // ---------- Default (Franhive) ----------
  $default = [
    'name'      => 'Franhive',
    'logo'      => 'https://eyd.franhive.com/uploads/70d631f14dbe43e3c32c502c037ad94b.png',
    'banner'    => 'https://demo.franhive.com/uploads/68c5ce6764788_franhive-banner-image.png', // leave empty to use gradient fallback
    'home_url'  => 'https://franhive.com/',
    'login_cta' => 'Login to Franhive LMS',
    'social'    => [
      // 'facebook' => '',
      // 'linkedin' => '',
    ],
    'support_text' => 'Questions? Write to info@franhive.com',
    'social_hint'  => 'Want to know more? Reach out to our team.',
  ];

  $cfg = $tenants[$subdomain] ?? $default;

  // Small helpers for safe output
  function e($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
?>
<head>
  <!-- Basic Page Info -->
  <meta charset="utf-8">
  <title><?php echo e($cfg['name']); ?> - One Stop Solution to Manage Your Business</title>

  <!-- Site favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('vendors/images/apple-touch-icon.png'); ?>">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('vendors/images/favicon-32x32.png'); ?>">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('vendors/images/favicon-16x16.png'); ?>">

  <!-- Mobile Specific Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="<?php echo base_url('vendors/styles/core.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('vendors/styles/icon-font.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('src/plugins/datatables/css/dataTables.bootstrap4.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('src/plugins/datatables/css/responsive.bootstrap4.min.css'); ?>">
  <link rel="stylesheet" href="<?php echo base_url('vendors/styles/style.css'); ?>">

  <link rel="stylesheet" href="https://unpkg.com/tippy.js/dist/tippy.css">
  <script src="https://unpkg.com/tippy.js@6.3.1/dist/tippy.all.min.js"></script>

  <style>
    :root{
      --brand:#fdb02f;
      --overlay: rgba(17,24,39,.65);
    }

    /* ========= Full-screen background + overlay ========= */
    body.login-page{
      min-height: 100vh;
      margin:0;
      font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, 'Helvetica Neue', Arial, 'Apple Color Emoji','Segoe UI Emoji';
      color:#111827;
      background:
        <?php if(!empty($cfg['banner'])): ?>
          url('<?php echo e($cfg['banner']); ?>') center/cover no-repeat fixed;
        <?php else: ?>
          radial-gradient(1200px 600px at 80% -10%, #ffe7b6 0%, transparent 60%),
          radial-gradient(1200px 600px at 10% 110%, #c6e6ff 0%, transparent 60%),
          linear-gradient(135deg, #fff8ec 0%, #eef7ff 100%);
        <?php endif; ?>;
      position:relative;
    }
    body.login-page::before{
      content:"";
      position:absolute; inset:0;
      background: var(--overlay);
      z-index:0;
    }

    /* ========= Floating header over background ========= */
    .login-header{
      position:absolute; top:0; left:0; right:0;
      z-index:2;
      background:transparent;
      padding: 14px 0;
    }

    
    .brand-logo img{ height:44px; width:auto; display:block; filter: drop-shadow(0 2px 8px rgba(0,0,0,.35)); }
    .login-menu a{
      font-size:18px; font-weight:600; color:#fff; text-decoration:none;
      text-shadow: 0 1px 2px rgba(0,0,0,.35);
    }
    .login-menu a:hover{ opacity:.92; }

    /* ========= Centered glass popup ========= */
    .hero-overlay{
      position:relative; z-index:1;
      min-height: 100vh;
      display:flex; flex-direction:column; justify-content:center; align-items:center;
      padding: clamp(18px, 4vw, 40px);
    }

    .login-box{
      width:100%; max-width: 440px;
      border-radius: 20px;
      background: rgba(255,255,255,.85);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border:1px solid rgba(255,255,255,.2);
      box-shadow: 0 20px 40px rgba(0,0,0,.25), 0 2px 8px rgba(0,0,0,.12);
      padding: clamp(20px, 3vw, 32px);
    }

    h2.text-brandcolor{ color:var(--brand); font-weight:800; letter-spacing:.2px; line-height:1.2; }

    label{ font-weight:600; margin-bottom:6px; color:#111827; }
    .form-control-lg{ border-radius:12px !important; height:48px; }
    .input-group .input-group-text{ border-radius: 0 12px 12px 0; }

    .btn.btn-primary{
      background: var(--brand); border-color: var(--brand);
      border-radius: 12px; font-weight:700;
      transition: transform .08s ease, box-shadow .2s ease;
    }
    .btn.btn-primary:hover{
      transform: translateY(-1px);
      box-shadow: 0 10px 18px rgba(253,176,47,.35);
    }

    /* Socials */
    .social-links{
      display:flex; gap:10px; flex-wrap:wrap; justify-content:center; margin:14px 0 4px;
    }
    .social-btn{
      width:42px; height:42px; border-radius:50%;
      display:inline-flex; align-items:center; justify-content:center;
      background:#111827; color:#fff; text-decoration:none; border:1px solid rgba(0,0,0,.08);
      transition:transform .15s ease, box-shadow .2s ease, background-color .2s ease, opacity .2s ease;
      outline:none;
    }
    .social-btn svg{ width:20px; height:20px; }
    .social-btn:hover{ transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,.12); }
    .social-btn:focus-visible{ box-shadow:0 0 0 3px rgba(253,176,47,.45); }
    .social-hint{ font-size:12px; color:#374151; text-align:center; margin-top:4px; }

    .social-btn.wa{ background:#25D366; }
    .social-btn.msg{ background:#0084FF; }
    .social-btn.fb:hover{ background:#1877F2; }
    .social-btn.ig:hover{ background:#E1306C; }
    .social-btn.yt:hover{ background:#FF0000; }
    .social-btn.in:hover{ background:#0A66C2; }

    .box-shadow{ box-shadow: 0 4px 14px rgba(0,0,0,.06) }
    .border-radius-10{ border-radius:12px }

    /* Accessibility: reduced motion */
    @media (prefers-reduced-motion: reduce){
      .social-btn, .btn.btn-primary{ transition:none !important; transform:none !important; }
    }

    /* Responsive tweaks */
    @media (max-width: 992px){
      .brand-logo img{ height:38px; }
      .login-menu a{ font-size:16px; }
    }
    @media (max-width: 576px){
      .brand-logo img{ height:32px; }
      .login-menu a{ font-size:15px; }
    }
  </style>
</head>

<body class="login-page">
  <!-- Floating header over background -->
  <div class="login-header">
    <div class="container-fluid d-flex justify-content-between align-items-center">
      <div class="brand-logo">
        <a href="<?php echo e(base_url('login')); ?>">
          <img src="<?php echo e($cfg['logo']); ?>" alt="<?php echo e($cfg['name']); ?> Logo" loading="lazy" decoding="async">
        </a>
      </div>
      <div class="login-menu">
        <ul class="mb-0">
          <li><a href="<?php echo e($cfg['home_url']); ?>" target="_blank" rel="noopener">Visit <?php echo e($cfg['name']); ?></a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Centered glass popup -->
  <div class="hero-overlay">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-11 col-md-9 col-lg-6 col-xl-5">
          <div class="login-box">
            <div class="login-title mb-3">
              <h2 class="text-center text-brandcolor"><?php echo e($cfg['login_cta']); ?></h2>
            </div>

            <form method="post" action="<?php echo base_url('login-auth'); ?>" novalidate>
              <label for="email">Email</label>
              <div class="input-group custom mb-3">
                <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="your@email.com" autocomplete="username" required aria-required="true">
                <div class="input-group-append">
                  <span class="input-group-text" aria-hidden="true"><i class="icon-copy dw dw-user1"></i></span>
                </div>
              </div>

              <label for="password">Password</label>
              <div class="input-group custom mb-2">
                <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="**********" autocomplete="current-password" required aria-required="true">
                <div class="input-group-append">
                  <span class="input-group-text" aria-hidden="true"><i class="dw dw-padlock1"></i></span>
                </div>
              </div>

              <!-- Flash Message for Login Error -->
              <?php if ($this->session->flashdata('login_error')): ?>
                <div class="alert alert-danger mt-2" role="alert" aria-live="assertive">
                  <?php echo $this->session->flashdata('login_error'); ?>
                </div>
              <?php endif; ?>

             

              <div class="d-flex justify-content-between align-items-center my-2">
              <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
                <label class="custom-control-label" for="customCheck1">Remember me !</label>
              </div>

              <a href="<?php echo base_url('forgot-password'); ?>" class="small" style="color:#0084FF;">Forgot password?</a>
            </div>


              <div class="row mt-3">
                <div class="col-sm-12">
                  <input class="btn btn-primary btn-lg btn-block" type="submit" value="Login">
                </div>
              </div>
            </form>

            <!-- Socials (only render if configured) -->
            <?php if (!empty($cfg['social']) && count(array_filter($cfg['social'])) > 0): ?>
              <div class="mt-3 text-center">
                <div class="social-hint"><?php echo e($cfg['social_hint']); ?></div>
                <div class="social-links" aria-label="Connect with us on social">
                  <?php if (!empty($cfg['social']['whatsapp'])): ?>
                  <a class="social-btn wa" href="<?php echo e($cfg['social']['whatsapp']); ?>" target="_blank" rel="noopener" aria-label="Open WhatsApp chat" data-tippy-content="WhatsApp">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M12 2.6a8.9 8.9 0 00-7.8 13.2l-1 3.7 3.8-1A8.9 8.9 0 1012 2.6Z" stroke="currentColor" stroke-width="1.6"/>
                      <path d="M8.9 10.2c.1-.2.3-.3.5-.2l1.1.5c.2.1.3.3.2.5-.2.5 0 1.1.4 1.5l.4.4c.4.4 1 .6 1.5.4.2-.1.4 0 .5.2l.5 1.1c.1.2 0 .4-.2.5-1.1.6-2.5.4-3.5-.6l-.9-.9c-1-1-1.2-2.4-.5-3.4Z" fill="currentColor"/>
                    </svg>
                  </a>
                  <?php endif; ?>

                  <?php if (!empty($cfg['social']['messenger'])): ?>
                  <a class="social-btn msg" href="<?php echo e($cfg['social']['messenger']); ?>" target="_blank" rel="noopener" aria-label="Message on Facebook Messenger" data-tippy-content="Messenger (Page)">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M12 2.5C6.7 2.5 2.5 6.4 2.5 11.3c0 2.8 1.4 5.2 3.5 6.8v3.4l3.3-1.8c.9.3 1.8.4 2.7.4 5.3 0 9.5-3.9 9.5-8.8S17.2 2.5 12 2.5Z" stroke="currentColor" stroke-width="1.5"/>
                      <path d="M7 13.2l3.2-3 2.3 2 3.5-3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </a>
                  <?php endif; ?>

                  <?php if (!empty($cfg['social']['messenger_personal'])): ?>
                  <a class="social-btn msg" href="<?php echo e($cfg['social']['messenger_personal']); ?>" target="_blank" rel="noopener" aria-label="Message trainer on Messenger" data-tippy-content="Messenger (Trainer)">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M12 2.5C6.7 2.5 2.5 6.4 2.5 11.3c0 2.8 1.4 5.2 3.5 6.8v3.4l3.3-1.8c.9.3 1.8.4 2.7.4 5.3 0 9.5-3.9 9.5-8.8S17.2 2.5 12 2.5Z" stroke="currentColor" stroke-width="1.5"/>
                      <path d="M7 13.2l3.2-3 2.3 2 3.5-3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </a>
                  <?php endif; ?>

                  <?php if (!empty($cfg['social']['facebook'])): ?>
                  <a class="social-btn fb" href="<?php echo e($cfg['social']['facebook']); ?>" target="_blank" rel="noopener" aria-label="Visit Facebook" data-tippy-content="Facebook">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M15 8h-2a1 1 0 00-1 1v2h3l-.5 3h-2.5v8h-3v-8h-2v-3h2V9a4 4 0 014-4h2v3z" fill="currentColor"/>
                    </svg>
                  </a>
                  <?php endif; ?>

                  <?php if (!empty($cfg['social']['instagram'])): ?>
                  <a class="social-btn ig" href="<?php echo e($cfg['social']['instagram']); ?>" target="_blank" rel="noopener" aria-label="Visit Instagram" data-tippy-content="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.5"/>
                      <circle cx="12" cy="12" r="4.2" stroke="currentColor" stroke-width="1.5"/>
                      <circle cx="17.3" cy="6.7" r="1.3" fill="currentColor"/>
                    </svg>
                  </a>
                  <?php endif; ?>

                  <?php if (!empty($cfg['social']['youtube'])): ?>
                  <a class="social-btn yt" href="<?php echo e($cfg['social']['youtube']); ?>" target="_blank" rel="noopener" aria-label="Visit YouTube" data-tippy-content="YouTube">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <rect x="2.5" y="6" width="19" height="12" rx="3" stroke="currentColor" stroke-width="1.5"/>
                      <path d="M10 9.5l5 2.5-5 2.5v-5z" fill="currentColor"/>
                    </svg>
                  </a>
                  <?php endif; ?>

                  <?php if (!empty($cfg['social']['linkedin'])): ?>
                  <a class="social-btn in" href="<?php echo e($cfg['social']['linkedin']); ?>" target="_blank" rel="noopener" aria-label="Visit LinkedIn" data-tippy-content="LinkedIn">
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                      <path d="M6 9h3v9H6zM7.5 6.5A1.5 1.5 0 107.5 3a1.5 1.5 0 000 3.5zM11 9h3v1.4h.1a3.3 3.3 0 012.9-1.6c3.1 0 3.7 2 3.7 4.6V18h-3v-3.6c0-.9 0-2-1.2-2s-1.4 1-1.4 2.1V18h-3V9z" fill="currentColor"/>
                    </svg>
                  </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endif; ?>

            <div class="row mt-2">
              <div class="col-sm-12 text-center">
                <span style="font-size: 11px;"><?php echo e($cfg['support_text']); ?></span>
              </div>
            </div>
          </div> <!-- /.login-box -->
        </div>
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="<?php echo base_url('vendors/scripts/core.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/scripts/script.min.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/scripts/process.js'); ?>"></script>
  <script src="<?php echo base_url('vendors/scripts/layout-settings.js'); ?>"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script>
    // Tooltips for social buttons
    tippy('.social-btn', {
      theme: 'light',
      animation: 'scale',
      delay: [80, 0],
      arrow: true
    });

    // Ensure logo has lazy + alt
    (function(){
      var logo = document.querySelector('.brand-logo img');
      if(logo){
        logo.setAttribute('loading', 'lazy');
        if(!logo.getAttribute('alt')) logo.setAttribute('alt', '<?php echo e($cfg['name']); ?> Logo');
      }
    })();
  </script>
</body>
</html>
