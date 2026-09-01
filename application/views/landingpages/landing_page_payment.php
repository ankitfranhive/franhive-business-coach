<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($landing_page['LANDING_PAGE_NAME']); ?></title>


    <?php
// --- Social share meta (OG + Twitter) ---
$share_title = !empty($landing_page['LANDING_PAGE_NAME'])
  ? $landing_page['LANDING_PAGE_NAME']
  : 'Empower Your Destiny — Destiny Duo Certification';

$raw_left = isset($landing_page['LANDING_PAGE_LEFT_CONTENT'])
  ? strip_tags($landing_page['LANDING_PAGE_LEFT_CONTENT'])
  : '';

$desc_clean = trim(preg_replace('/\s+/', ' ', $raw_left));
$share_desc = mb_substr($desc_clean, 0, 160) . (mb_strlen($desc_clean) > 160 ? '…' : '');

// Prefer a dedicated share image if you add one; else fall back to banner
$share_image = !empty($landing_page['SHARE_IMAGE'])
  ? base_url(trim($landing_page['SHARE_IMAGE']))
  : (!empty($landing_page['BANNER_IMAGE']) ? htmlspecialchars($landing_page['BANNER_IMAGE']) : base_url('images/eyd-share-default.jpg'));

// Current absolute URL
$share_url = function_exists('current_url') ? current_url()
  : ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!-- Primary SEO -->
<meta name="description" content="<?= htmlspecialchars($share_desc); ?>">

<!-- Open Graph / Facebook / LinkedIn -->
<meta property="og:type" content="website">
<meta property="og:site_name" content="Empower Your Destiny">
<meta property="og:title" content="<?= htmlspecialchars($share_title); ?>">
<meta property="og:description" content="<?= htmlspecialchars($share_desc); ?>">
<meta property="og:image" content="<?= $share_image; ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= $share_url; ?>">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($share_title); ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($share_desc); ?>">
<meta name="twitter:image" content="<?= $share_image; ?>">
<!-- Optional if you have a handle -->
<!-- <meta name="twitter:site" content="@your_handle"> -->

<!-- Canonical -->
<link rel="canonical" href="<?= $share_url; ?>">








    <link rel="shortcut icon" type="image/png" href="https://www.franhive.com/images/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;800&family=Inter:wght@400;600&display=swap" rel="stylesheet">

    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

        /* ===== Page backgrounds (choose ONE by adding a class to <body>) ===== */

/* Base ensures pseudo elements sit behind content */
body.bg-skin { background:#f8fafc; position:relative; }

/* --- Option A: Soft mesh gradient (emerald + sky) --- */
body.bg-mesh::before{
  content:""; position:fixed; inset:0; z-index:-2;
  background:
    radial-gradient(900px 520px at 15% -10%, rgba(16,185,129,.16), transparent 60%),
    radial-gradient(900px 520px at 100% 20%, rgba(14,165,233,.13), transparent 60%),
    radial-gradient(1000px 600px at 50% 120%, rgba(16,185,129,.08), transparent 60%),
    linear-gradient(180deg,#ffffff 0%, #f7fafc 50%, #eef2f7 100%);
}

/* Subtle grain for depth (tiny, lightweight pattern) */
body.bg-mesh::after{
  content:""; position:fixed; inset:0; z-index:-1; opacity:.035; pointer-events:none;
  background-image:
    radial-gradient(circle at 1px 1px, rgba(15,23,42,.25) 1px, transparent 1px);
  background-size: 32px 32px; /* adjust granularity */
}

/* --- Option B: Logo watermark (very subtle brand texture) --- */
body.bg-logo::before{
  content:""; position:fixed; inset:0; z-index:-2;
  background: linear-gradient(180deg,#ffffff 0%, #f8fafb 50%, #eef2f7 100%);
}
body.bg-logo::after{
  content:""; position:fixed; inset:0; z-index:-1; opacity:.03; pointer-events:none;
  background-image: url("https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png");
  background-repeat: repeat;
  background-size: 220px auto; /* change scale if needed */
  filter: grayscale(100%);
}

/* Optional: add a gentle halo behind key cards */
.with-halo{ position:relative; }
.with-halo::before{
  content:""; position:absolute; inset:-2px; border-radius:18px; z-index:-1;
  background:
    radial-gradient(600px 140px at 50% -12%, rgba(16,185,129,.14), transparent 60%);
}

/* Respect reduced motion (no animated backgrounds here, but future-proofing) */
@media (prefers-reduced-motion: reduce){
  .with-halo::before{ background:
    radial-gradient(600px 140px at 50% -12%, rgba(16,185,129,.10), transparent 60%);
  }
}


        .my-header .mh-inner{
  display:flex; align-items:center; justify-content:space-between; gap:12px;
}

        .alert-success {
            color: #333333;
            background-color: #F3F6F8;
            border-color: #c3e6cb;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .my-header {
            background-color: #f9af30;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
        }
        .content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .main-content, .side-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .main-content { flex: 2; }
        .side-content {
            flex: 1;
            overflow-y: auto;
            max-height: 500px;
        }
        .contact-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
        }
        .contact-form input, .contact-form textarea { border-radius: 5px; }
        .contact-form button {
            background-color: #f9af30;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
        }
        .contact-form button:hover { background-color: #e89c28; }
        @media (max-width: 768px) { .content { flex-direction: column; } }

        /* ===========================
           Payment block — enhanced UI
           (green/neutral, compact, premium)
           =========================== */
        #payment-block {
            --accent: #10b981;    /* emerald */
            --accent-2: #059669;  /* deeper emerald */
            --accent-on: #ffffff;
            --surface: #ffffff;
            --muted: #6b7280;     /* slate-500 */
            --chip: #f3f6f8;      /* light surface */
            --ring: rgba(16,185,129,.35);
        }

        #payment-block .pcard{
            border: 0; border-radius: 16px; box-shadow: 0 12px 28px rgba(17,24,39,.08);
            overflow: hidden; background: var(--surface);
        }
        #payment-block .pcard-head {
          background: linear-gradient(135deg, #f9af30, #ffffff);
          color: #333333;
          padding: 18px 18px;
          display: flex;
          align-items: center;
          gap: 12px;
      }
        #payment-block .pcard-head .icon{
            width: 42px; height: 42px; border-radius: 50%; background: var(--accent-on);
            display: inline-flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 22px rgba(255,255,255,.25);
        }
        #payment-block .pcard-body{ padding: 18px; }
        #payment-block .muted{ color: var(--muted); }

        #payment-block .rowline{
            display: flex; align-items: center; justify-content: space-between; padding: 10px 0;
        }
        #payment-block .rowline + .rowline{ border-top: 1px dashed #e5e7eb; }

        #payment-block .price-big{ font-size: 1.45rem; font-weight: 700; }
        #payment-block .strike{ text-decoration: line-through; color: #9ca3af; }
        #payment-block .discount-row{ color: var(--accent-2); }

        /* Coupon: shorter input + chunkier button */
        #payment-block .coupon-wrap{
            display: flex; align-items: center; gap: 10px; margin-top: 10px; max-width: 460px;
        }
        #payment-block .coupon-wrap .form-control{
            flex: 0 1 210px; height: 40px; border-radius: 10px;
        }
        #payment-block .btn-apply{
            padding: 8px 16px; font-weight: 700; border-radius: 10px;
            border: 1px solid #e5e7eb; background: #fff; color: #111827;
            transition: box-shadow .15s ease, transform .03s ease, background-color .15s ease;
        }
        #payment-block .btn-apply:hover{ background: #f9fafb; }
        #payment-block .btn-apply:active{ transform: translateY(1px); }
        #payment-block .coupon-help{ font-size: .85rem; color: var(--muted); margin-top: 6px; }

        /* Promo nudge (Save up to 50%) */
        #payment-block .promo-nudge{
          display:flex; align-items:center; gap:10px;
          margin-top:8px; padding:10px 12px; border-radius:12px;
          background: linear-gradient(90deg, rgba(16,185,129,.08), rgba(16,185,129,.03));
          border: 1px dashed rgba(16,185,129,.35);
          color:#065f46; font-size:.92rem;
        }
        #payment-block .promo-nudge .burst{
          width:28px; height:28px; border-radius:50%;
          background:#10b981; display:inline-flex; align-items:center; justify-content:center;
          box-shadow:0 4px 12px rgba(16,185,129,.35);
        }
        #payment-block .promo-nudge .burst svg{ width:16px; height:16px; }
        #payment-block .promo-nudge .highlight{ font-weight:700; }
        #payment-block .promo-nudge .sub{ color:#0f5132; opacity:.9; }

        /* Trust & badges */
        #payment-block .secure{
            display: flex; align-items: center; gap: 8px; font-size: .9rem; color: #374151;
            background: var(--chip); border-radius: 10px; padding: 10px 12px; margin-top: 12px;
        }
        #payment-block .badges{ display: flex; gap: 8px; flex-wrap: wrap; margin-top: 10px; }
        #payment-block .badge-pay{
            background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 6px 10px; font-size: .85rem; color: #374151;
        }

        /* Full-width Pay button with centered prices */
        #payment-block .btn-row{ margin-top:14px; }
        #payment-block .btn-accent{
            background: var(--accent); color: var(--accent-on); border: none; font-weight: 700;
            border-radius: 10px; padding: 12px 16px; width: 100%; min-width: 0;
            display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px;
            box-shadow: 0 6px 16px rgba(16,185,129,.24);
            transition: box-shadow .15s ease, transform .03s ease, background-color .15s ease;
        }
        #payment-block .btn-accent:hover{
            background: var(--accent-2); color: var(--accent-on);
            box-shadow: 0 8px 18px rgba(5,150,105,.28);
        }
        #payment-block .btn-accent:active{ transform: translateY(1px); }
        #payment-block .btn-accent .btn-title{ font-size: 1rem; }
        #payment-block .btn-accent .price-wrap{ display: flex; align-items: baseline; gap: 8px; }
        #payment-block .btn-accent .price-wrap .orig{
            text-decoration: line-through; color: #d1d5db; font-weight: 600;
        }
        #payment-block .btn-accent .price-wrap .final{ font-weight: 800; font-size: 1.1rem; }

        /* Focus states */
        #payment-block input.form-control:focus,
        #payment-block .btn-apply:focus,
        #payment-block .btn-accent:focus{
            outline: none !important; box-shadow: 0 0 0 4px var(--ring);
        }

        /* Mobile */
        @media (max-width: 575.98px){
            #payment-block .coupon-wrap{ max-width: 100%; }
            #payment-block .coupon-wrap .form-control{ flex: 1 1 auto; }
        }


        /* ===== Destiny Duo content polish ===== */
        .duo-hero {
          background: linear-gradient(180deg, #ffffff, #f8fafb);
          border: 1px solid #eef2f7;
          border-radius: 14px;
          padding: 20px;
          box-shadow: 0 12px 24px rgba(17,24,39,.06);
        }
        .duo-kicker { color:#059669; font-weight:700; letter-spacing:.03em; text-transform:uppercase; font-size:.9rem; }
        .duo-title { font-size:1.6rem; font-weight:800; margin:6px 0 8px; }
        .duo-sub { color:#4b5563; font-size:1rem; }

        .duo-badges { display:flex; gap:8px; flex-wrap:wrap; margin-top:12px; }
        .duo-badge {
          background:#f3f6f8; color:#374151; border:1px solid #e5e7eb;
          border-radius:999px; padding:6px 10px; font-size:.85rem; font-weight:600;
        }

        .duo-section { margin-top:22px; }
        .duo-h2 { font-size:1.15rem; font-weight:800; margin-bottom:10px; color:#111827; }
        .duo-lead { color:#374151; }

        .duo-list { list-style:none; padding-left:0; margin:8px 0 0; }
        .duo-list li {
          position:relative; padding-left:28px; margin:8px 0; color:#374151;
        }
        .duo-list li::before {
          content:''; position:absolute; left:0; top:3px; width:18px; height:18px; border-radius:50%;
          background:#10b981; box-shadow:0 3px 8px rgba(16,185,129,.35);
          -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill=\"white\" viewBox=\"0 0 24 24\"><path d=\"M20 6L9 17l-5-5\"/></svg>') center/14px 14px no-repeat;
          mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill=\"white\" viewBox=\"0 0 24 24\"><path d=\"M20 6L9 17l-5-5\"/></svg>') center/14px 14px no-repeat;
        }

        .duo-cards .card {
          border-radius: 14px; border:1px solid #eef2f7; box-shadow:0 8px 18px rgba(17,24,39,.06);
        }
        .duo-cards .card-header {
          background:#ffffff; border-bottom:1px dashed #e5e7eb; font-weight:700;
        }
        .duo-tag { font-size:.8rem; font-weight:700; color:#065f46; background:#ecfdf5; border:1px solid #d1fae5; border-radius:999px; padding:4px 8px; }

        .duo-pricing .price { font-size:1.4rem; font-weight:800; color:#111827; }
        .duo-pricing .save { color:#065f46; font-weight:700; }

        .duo-cta { margin-top:12px; }
        .duo-cta .btn-duo {
          background:#10b981; color:#fff; font-weight:700; border:none; border-radius:10px; padding:10px 14px;
        }
        .duo-cta .btn-duo:hover { background:#059669; color:#fff; }

        /* FAQ accordion */
        #faqAccordion .card { border-radius:12px; overflow:hidden; border:1px solid #e5e7eb; }
        #faqAccordion .card + .card { margin-top:10px; }
        #faqAccordion .card-header {
          background:#fff; border-bottom:1px dashed #e5e7eb; padding:0;
        }
        #faqAccordion .btn-faq {
          display:flex; justify-content:space-between; align-items:center; width:100%;
          text-align:left; padding:14px 16px; font-weight:700; color:#111827;
        }
        #faqAccordion .btn-faq .small { color:#6b7280; font-weight:600; }
        #faqAccordion .btn-faq:focus { box-shadow:none; }
        #faqAccordion .chev { transition: transform .2s ease; }
        #faqAccordion .collapsed .chev { transform: rotate(0deg); }
        #faqAccordion .chev.rot { transform: rotate(180deg); }

        /* ===== Split hero (half image, half content) ===== */
.hero-split{
  border:1px solid #eef2f7; border-radius:16px;
  background:linear-gradient(180deg,#ffffff,#f9fafb);
  box-shadow:0 12px 24px rgba(17,24,39,.06);
  padding:22px 20px;
}
.kicker{
  color:#059669; font-weight:700; letter-spacing:.04em; text-transform:uppercase; font-size:.9rem;
}
.display-title{
  font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;
  font-weight:800; line-height:1.1;
  font-size:clamp(1.8rem,1.1rem + 1.2vw,2.4rem);
  margin:.25rem 0 .5rem;
  /* background:linear-gradient(90deg,#0ea5e9,#10b981); */
  background: linear-gradient(90deg, #f9af30, #101827);
  -webkit-background-clip:text; background-clip:text; color:transparent;
}
.hero-lead{ color:#4b5563; font-size:1.02rem; }
.mini-benefits{ list-style:none; padding-left:0; margin:10px 0 0; }
.mini-benefits li{
  position:relative; padding-left:26px; margin:6px 0; color:#374151;
}
.mini-benefits li::before{
  content:''; position:absolute; left:0; top:3px; width:18px; height:18px; border-radius:50%;
  background:#10b981; box-shadow:0 3px 8px rgba(16,185,129,.35);
  -webkit-mask:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>') center/14px 14px no-repeat;
          mask:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>') center/14px 14px no-repeat;
}
.hero-cta{ display:flex; gap:10px; flex-wrap:wrap; margin-top:12px; }
.btn-hero{
  background:#10b981; color:#fff; font-weight:700; border:none; border-radius:10px; padding:10px 14px;
}
.btn-hero:hover{ background:#059669; color:#fff; }
.btn-outline-hero{
  background:#fff; color:#111827; font-weight:700; border:1px solid #e5e7eb; border-radius:10px; padding:10px 14px;
}
.btn-outline-hero:hover{ background:#f9fafb; }

.hero-image-wrap{
  position:relative; border-radius:16px; overflow:hidden;
  box-shadow:0 10px 24px rgba(17,24,39,.12);
  isolation:isolate; /* for overlay badge */
}
.hero-image-wrap::before{
  content:''; position:absolute; inset:-2px; border-radius:18px;
  background:linear-gradient(135deg,rgba(14,165,233,.25),rgba(16,185,129,.25));
  z-index:-1; filter:blur(20px);
}
.hero-img{ width:100%; height:auto; display:block; }
.img-badge{
  position:absolute; left:12px; top:12px; background:#ecfdf5; color:#065f46; border:1px solid #d1fae5;
  font-weight:700; border-radius:999px; padding:6px 10px; font-size:.8rem;
}

/* Optional: soften your old orange header if you keep it elsewhere */
/* .my-header{ display:none; }  */
/* hide old header bar since hero now shows the title */

/* ===== Register card (keeps same form IDs/fields) ===== */
#register-block .regcard{
  border:1px solid #eef2f7; border-radius:16px; overflow:hidden;
  background:#fff; box-shadow:0 12px 24px rgba(17,24,39,.06);
}
#register-block .reg-head{
    background: linear-gradient(135deg, #f9af30, #ffffff);
    color: #333333;
    padding: 18px 18px;
    display: flex;
    align-items: center;
    gap: 12px;
}
/* #register-block .reg-head{
  background:linear-gradient(135deg,#10b981,#059669);
  color:#ffffff; padding:18px; display:flex; align-items:center; gap:12px;
} */
#register-block .reg-icon{
  width:44px; height:44px; border-radius:50%; background:#ffffff;
  display:inline-flex; align-items:center; justify-content:center;
  box-shadow:0 8px 22px rgba(255,255,255,.25);
}
#register-block .reg-title{
  font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;
  font-weight:800; line-height:1.1; margin:0;
  font-size:clamp(1.2rem,.9rem + .6vw,1.6rem);
}
#register-block .reg-sub{ margin:2px 0 0; opacity:.95; font-weight:600; }

#register-block .reg-body{ padding:18px; }
#register-block .form-row-gap{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
@media (max-width:575.98px){ #register-block .form-row-gap{ grid-template-columns:1fr; } }

#register-block label{ font-weight:700; color:#111827; margin-bottom:6px; }
#register-block .form-control{
  border-radius:10px; height:44px; padding-left:12px; border:1px solid #e5e7eb;
}
#register-block .form-control:focus{
  box-shadow:0 0 0 4px rgba(16,185,129,.25); border-color:#10b981;
}

#register-block .hint{ color:#6b7280; font-size:.9rem; margin-top:4px; }
#register-block .privacy{
  margin-top:8px; background:#f3f6f8; border:1px dashed #e5e7eb;
  border-radius:10px; padding:10px 12px; color:#374151; font-size:.9rem;
}

#register-block .actions{ margin-top:12px; }
#register-block .btn-reg{
  width:100%; border:none; border-radius:10px; padding:12px 16px; font-weight:800;
  background:#10b981 !important; color:#ffffff; box-shadow:0 6px 16px rgba(16,185,129,.24);
  transition:transform .03s ease, box-shadow .15s ease, background-color .15s ease;
}
#register-block .btn-reg:hover{ background:#059669 !important; box-shadow:0 8px 18px rgba(5,150,105,.28); }
#register-block .btn-reg:active{ transform:translateY(1px); }
#register-block .btn-reg .chev{ margin-left:6px; font-weight:900; }

#register-block #contact-status .alert{
  border-radius:10px; border:1px solid #e5e7eb;
}

/* ===== Coach Section ===== */

/* Coach: split row (intro) + full-width row (details) */
#coach .coach-card{ padding:18px; }
#coach .coach-intro{ margin:0; }
#coach .coach-intro .col-md-8{ display:flex; flex-direction:column; }
#coach .coach-intro .coach-name{ margin-top:2px; }

#coach .coach-details{
  margin-top:14px; padding-top:14px;
  border-top:1px dashed #e5e7eb; /* subtle separation between rows */
}

/* (Keep your existing #coach styles from before) */


#coach .coach-card{
  border:1px solid #eef2f7; border-radius:16px; background:#fff;
  box-shadow:0 12px 24px rgba(17,24,39,.06); padding:18px;
}
#coach .coach-title{ font-size:1.25rem; font-weight:800; color:#111827; margin-bottom:10px; }
#coach .coach-name{
  font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;
  font-weight:800; font-size:1.35rem; margin-bottom:6px; color:#0f172a;
}
#coach .coach-sub{ color:#059669; font-weight:700; letter-spacing:.02em; margin-bottom:8px; }
#coach .coach-text{ color:#374151; }
#coach .coach-list{ list-style:none; padding-left:0; margin:8px 0 0; }
#coach .coach-list li{
  position:relative; padding-left:26px; margin:6px 0; color:#374151;
}
#coach .coach-list li::before{
  content:''; position:absolute; left:0; top:3px; width:18px; height:18px; border-radius:50%;
  background:#10b981; box-shadow:0 3px 8px rgba(16,185,129,.35);
  -webkit-mask:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>') center/14px 14px no-repeat;
          mask:url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" fill="white" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>') center/14px 14px no-repeat;
}

/* Photo */
#coach .photo-wrap{
  position:relative; border-radius:16px; overflow:hidden;
  box-shadow:0 10px 24px rgba(17,24,39,.12);
}
#coach .photo-wrap::before{
  content:''; position:absolute; inset:-2px; border-radius:18px;
  background:linear-gradient(135deg,rgba(14,165,233,.25),rgba(16,185,129,.25));
  z-index:-1; filter:blur(20px);
}
#coach .coach-img{ width:100%; height:auto; display:block; }

/* Contact chips */
#coach .coach-cta{ display:flex; gap:10px; flex-wrap:wrap; margin-top:12px; }
#coach .btn-msg, #coach .btn-wa{
  display:inline-flex; align-items:center; gap:8px; font-weight:800; border-radius:10px; padding:10px 14px;
  border:1px solid #e5e7eb; background:#fff; color:#111827;
  transition:box-shadow .15s ease, transform .03s ease, background-color .15s ease;
}
#coach .btn-msg:hover, #coach .btn-wa:hover{ background:#f9fafb; }
#coach .btn-msg svg, #coach .btn-wa svg{ width:18px; height:18px; }

/* Brand accents */
#coach .btn-msg .dot{ color:#0084FF; }   /* Messenger blue */
#coach .btn-wa .dot{ color:#25D366; }    /* WhatsApp green */

/* ===== Help / Connect (3 options) ===== */
#help-connect .connect-card{
  border:1px solid #eef2f7; border-radius:16px; background:#fff;
  box-shadow:0 12px 24px rgba(17,24,39,.06); padding:18px;
}
#help-connect .connect-title{ font-weight:800; font-size:1.15rem; margin-bottom:6px; color:#0f172a; }
#help-connect .connect-sub{ color:#374151; }
#help-connect .connect-cta{ display:flex; flex-wrap:wrap; gap:10px; margin-top:12px; }

#help-connect .chip{
  display:inline-flex; align-items:center; gap:8px; border:1px solid #e5e7eb;
  background:#fff; color:#111827; font-weight:800; border-radius:10px; padding:10px 14px;
  transition:background-color .15s ease, box-shadow .15s ease, transform .03s ease;
}
#help-connect .chip:hover{ background:#f9fafb; }
#help-connect .chip:active{ transform:translateY(1px); }
#help-connect .chip svg{ width:18px; height:18px; }

/* brand dots */
#help-connect .dot-msg{ color:#0084FF; }  /* Messenger */
#help-connect .dot-wa{ color:#25D366; }   /* WhatsApp */

/* small toast for Messenger copy helper */
#connect-copy-toast{
  position:fixed; left:50%; bottom:24px; transform:translateX(-50%);
  background:#111827; color:#fff; padding:8px 12px; border-radius:8px;
  box-shadow:0 8px 24px rgba(0,0,0,.25); font-size:.9rem; opacity:0;
  pointer-events:none; transition:opacity .25s ease; z-index:9999;
}
#connect-copy-toast.show{ opacity:1; }


/* Emphasized promo nudge */
#payment-block .promo-nudge.emphasis{
  border:2px solid rgba(16,185,129,.45);
  background:linear-gradient(90deg, rgba(16,185,129,.10), rgba(14,165,233,.08));
}
#payment-block .promo-nudge .headline{
  font-weight:800; font-size:1rem; color:#065f46; margin-bottom:2px;
}
#payment-block .promo-nudge .price-pill{
  display:inline-block; background:#111827; color:#fff; border-radius:999px;
  padding:4px 10px; font-weight:800; box-shadow:0 6px 16px rgba(17,24,39,.25);
}
#payment-block .promo-nudge .sub{ color:#0f5132; opacity:.95; }
#payment-block .promo-nudge .cta-link{
  font-weight:800; text-decoration:underline; margin-left:8px;
}

/* Fancy highlight for bundle price */
#payment-block .promo-nudge .price-pill{
  background: linear-gradient(90deg,#10b981,#0ea5e9);
  color:#fff; border:none;
  text-shadow: 0 1px 0 rgba(0,0,0,.15);
}

/* Subtle pop animation */
@keyframes popPulse {
  0%, 100% { transform: scale(1);   box-shadow: 0 6px 16px rgba(17,24,39,.25); }
  50%       { transform: scale(1.06); box-shadow: 0 10px 28px rgba(16,185,129,.35); }
}
#payment-block .promo-nudge .price-pill.pop {
  animation: popPulse 1.8s ease-in-out infinite;
}
#payment-block .promo-nudge .price-pill.pop:hover {
  animation-play-state: paused;  /* pause when user hovers */
}

/* Accessibility: respect reduced-motion */
@media (prefers-reduced-motion: reduce) {
  #payment-block .promo-nudge .price-pill.pop {
    animation: none;
  }
}

/* Header layout with brand logo */
/* === Attractive brand header === */
.my-header{
  display:block !important; /* ensure visible */
  background: linear-gradient(180deg,#ffffff,#f9fafb);
  border: 1px solid #eef2f7;
  border-radius: 16px;
  padding: 14px 16px;
  box-shadow: 0 12px 28px rgba(17,24,39,.06);
  position: relative;
  overflow: hidden;
}
.my-header::after{
  /* soft gradient accent edge */
  content:"";
  position:absolute; inset:-1px -1px auto -1px; height:6px; border-radius:16px 16px 0 0;
  background: linear-gradient(90deg,#0ea5e9,#10b981);
  opacity:.18;
}
.my-header .mh-wrap{
  display:flex; align-items:center; justify-content:space-between; gap:16px;
}
.mh-left{ display:flex; align-items:center; gap:14px; min-width:0; }

/* Logo capsule (dark so transparent logo pops) */
.mh-brand{
  background: linear-gradient(135deg,#0f172a,#1f2937);
  border:1px solid #0b1220;
  box-shadow:0 6px 16px rgba(2,6,23,.35);
  padding:8px 12px; border-radius:12px; flex:0 0 auto;
  transition: transform .06s ease, box-shadow .2s ease;
}
.mh-brand:hover{ transform: translateY(-1px); box-shadow:0 10px 22px rgba(2,6,23,.4); }
.mh-logo{ height:44px; width:auto; object-fit:contain; filter:saturate(1.05) contrast(1.05); }
@media (min-width:768px){ .mh-logo{ height:56px; } }

/* Title stack */
.mh-text{ min-width:0; }
.mh-kicker{
  color:#059669; font-weight:700; letter-spacing:.04em; text-transform:uppercase; font-size:.8rem; margin-bottom:2px;
}
.mh-title{
  font-family:'Poppins',system-ui,-apple-system,Segoe UI,Roboto,Inter,Arial,sans-serif;
  margin:0; line-height:1.1; font-weight:800;
  font-size:clamp(1.35rem,1rem + .9vw,2rem);
  color:#0f172a; position:relative; display:inline-block;
}
.mh-title::after{
  /* accent underline */
  content:""; position:absolute; left:0; right:0; bottom:-6px; height:3px; border-radius:999px;
  background: linear-gradient(90deg,#10b981,#0ea5e9);
  opacity:.5;
}

/* Credibility chips */
.mh-meta{ display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; }
.mh-chip{
  display:inline-flex; align-items:center; gap:8px;
  background:#fff; border:1px solid #e5e7eb; color:#111827;
  border-radius:999px; padding:6px 10px; font-size:.85rem; font-weight:700;
}
.mh-chip svg{ width:16px; height:16px; }

/* Optional CTA on the right */
.btn-mh{
  background:#10b981; color:#fff; font-weight:800; border:none; border-radius:10px; padding:10px 14px;
  box-shadow:0 6px 16px rgba(16,185,129,.24);
}
.btn-mh:hover{ background:#059669; color:#fff; box-shadow:0 8px 18px rgba(5,150,105,.28); }


/* === Mobile header fix: stack & center so no awkward blank space === */
@media (max-width: 575.98px){
  .my-header { padding: 12px; }
  .my-header .mh-wrap{
    flex-direction: column;              /* stack logo + text */
    align-items: center;                 /* center everything */
    text-align: center;                  /* center text */
    gap: 10px;
  }
  .mh-left{
    flex-direction: column;              /* ensure inner stack too */
    align-items: center;
    gap: 10px;
    min-width: auto;
  }
  .mh-brand{                              /* keep the logo nice & visible */
    order: 1;                             /* logo first */
    padding: 10px 14px;
    border-radius: 14px;
  }
  .mh-logo{ height: 52px; }               /* a touch larger on mobile */
  .mh-text{ order: 2; }

  .mh-kicker{ font-size: .78rem; }
  .mh-title{
    font-size: clamp(1.25rem, 5.2vw, 1.6rem);
  }
  .mh-title::after{                       /* center the accent underline */
    left: 50%; right: auto; transform: translateX(-50%);
    width: 60%;
  }
  .mh-meta{
    justify-content: center;              /* center the credibility chips */
  }

  /* Hide the desktop-only enroll button if you overrode classes earlier */
  .btn-mh{ display: none !important; }
}

/* Pay button price display */
#payment-block #pay-now .btn-title{
  font-weight:800; font-size:1.05rem;
}
#payment-block #pay-now .cta-price{
  display:none; /* hidden by default, shown in deposit mode */
  align-items:center; justify-content:center; gap:8px; margin-top:4px;
}
#payment-block #pay-now.is-deposit .cta-price{
  display:flex;
}
#payment-block #pay-now .pill-amount{
  display:inline-block; background:#111827; color:#fff; border-radius:999px;
  padding:2px 10px; font-weight:800; line-height:1.6;
}
#payment-block #pay-now .sep{ opacity:.5; }
#payment-block #pay-now .muted{ color:#6b7280; }

/* --- Fix: let the right column expand; no internal scrollbar --- */
.side-content {
  max-height: none !important;
  overflow: visible !important;
}

/* Optional: nicer testimonial layout; remove if you prefer 1-per-row */
.testimonials-list {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
}
@media (min-width: 992px) {
  .testimonials-list { grid-template-columns: 1fr; } /* keep single column on narrow sidebar */
}
.testimonial-image img {
  border-radius: 10px;
  box-shadow: 0 6px 16px rgba(0,0,0,.06);
}

/* --- Pricing polish --- */
.duo-pricing .price { font-weight: 700; font-size: 1.25rem; }
.duo-bundle-card {
  border: 1px solid #eef2f7;
  border-radius: 14px;
  background: linear-gradient(180deg,#ffffff 0%, #f9fafb 100%);
  box-shadow: 0 8px 22px rgba(17,24,39,.06);
}
.duo-save-badge {
  background: #059669;
  color: #fff;
  border-radius: 999px;
  padding: 6px 10px;
  font-size: .85rem;
  font-weight: 700;
}
.bundle-title { font-size: 1.05rem; }
.bundle-price-wrap { margin-top: 6px; }
.bundle-price { font-size: 1.8rem; font-weight: 800; line-height: 1.2; }
.bundle-savings .strike { text-decoration: line-through; color: #9ca3af; }

.db-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 14px;
}
@media (min-width: 768px) {
  .db-grid { grid-template-columns: 1fr 1fr; }
}
.db-item {
  background: #F3F6F8;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  padding: 14px 16px;
}
.db-kicker {
  font-size: .75rem; text-transform: uppercase; letter-spacing: .06em;
  color: #6b7280; margin-bottom: 2px;
}
.db-label { font-weight: 700; }
.db-amount { font-size: 1.25rem; font-weight: 800; }
.btn-duo {
  background: #f9af30; color: #111827; font-weight: 700; border-radius: 10px; border: none;
  padding: 10px 16px;
}
.btn-duo:hover { background: #e89c28; color: #111827; }


.pay-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
}
@media (min-width: 768px) {
  .pay-grid { grid-template-columns: 1fr 1fr; }
}

.btn-bank {
  background: #fff;
  border: 1.5px solid #e5e7eb;
  color: #111827;
  font-weight: 700;
  border-radius: 12px;
  padding: 14px 16px;
  text-align: left;
  box-shadow: 0 6px 14px rgba(17,24,39,.06);
}
.btn-bank:hover { border-color: #cbd5e1; box-shadow: 0 10px 22px rgba(17,24,39,.08); }

.btn-accent { width: 100%; } /* keep Stripe button full-width inside grid cell */
.btn-bank .btn-title, #pay-now .btn-title { font-weight: 800; }

.pay-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
@media (min-width: 768px) { .pay-grid { grid-template-columns: 1fr 1fr; } }

.btn-bank{
  background:#fff;border:1.5px solid #e5e7eb;color:#111827;font-weight:700;
  border-radius:12px;padding:14px 16px;text-align:left;box-shadow:0 6px 14px rgba(17,24,39,.06);
}
.btn-bank:hover{ border-color:#cbd5e1; box-shadow:0 10px 22px rgba(17,24,39,.08); }
.btn-accent{ width:100%; }
.btn-bank .btn-title, #pay-now .btn-title{ font-weight:800; }

/* Make the “remaining” text clearer + italic with a soft shadow */
span#bank-pay-remaining-text {
  color: #f9b43c;
  font-size: 10px;
  font-style: italic;              /* <- italic */
  font-weight: 400;                /* keep readable (you had 'initial') */
  text-shadow:
    0 1px 0 rgba(0,0,0,.25),       /* slight bottom edge */
    0 2px 4px rgba(0,0,0,.2);      /* soft glow for contrast */
}

.testimonial-video {
    text-align: center;
}

.bundle-logo {
  max-height: 60px;     /* keeps both logos uniform in height */
  margin: 0 10px;       /* spacing between logos */
  object-fit: contain;  /* keeps aspect ratio */
  transition: transform 0.3s ease, filter 0.3s ease;
}

.bundle-logo:hover {
  transform: scale(1.1);       /* subtle zoom effect */
  filter: drop-shadow(0 2px 6px rgba(0,0,0,0.2)); 
}


    </style>
</head>
<body class="bg-skin bg-logo">

    <div class="container">
     <!-- Header -->
<!-- Updated my-header -->
<div class="my-header">
  <div class="mh-wrap">
    <div class="mh-left">
      <!-- Brand logo -->
      <a class="mh-brand" href="<?= base_url(); ?>" aria-label="Empower Your Destiny — Home">
        <img
          class="mh-logo"
          src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png"
          alt="Empower Your Destiny (EYD) logo">
      </a>

      <!-- Title stack -->
      <div class="mh-text">
        <div class="mh-kicker">EYD Certification</div>
        <h1 class="mh-title"><?= htmlspecialchars($landing_page['LANDING_PAGE_NAME']); ?></h1>

        <!-- Credibility chips (optional but recommended) -->
        <div class="mh-meta">
          <span class="mh-chip">
            <!-- check icon -->
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M4 12l5 5L20 6" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            IICT Approved
          </span>
          <span class="mh-chip">
            <!-- video/online icon -->
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <rect x="3" y="6" width="14" height="12" rx="2" stroke="#0ea5e9" stroke-width="2"/>
              <path d="M17 10l4-2v8l-4-2v-4z" fill="#0ea5e9"/>
            </svg>
            Live Online
          </span>
          <!-- <span class="mh-chip">
           
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
              <path d="M12 3l2.5 4.9 5.4.8-3.9 3.8.9 5.4L12 15.8 7.1 18l.9-5.4L4 8.7l5.4-.8L12 3z" fill="#10b981"/>
            </svg>
            Dual Credentials
          </span> -->
        </div>
      </div>
    </div>

    <!-- Optional: quick jump to checkout -->
    <a href="#payment-block" class="btn-mh d-none d-md-inline-flex">Enroll now</a>
  </div>
</div>



<!-- Split HERO (half content, half image) -->
<section class="hero-split my-3 with-halo">

      <div class="row align-items-center">
        <div class="col-md-6">
          <div class="kicker">The Destiny Duo Bundle</div>
          <div class="display-title">Two powerful trainings. One life-changing journey.</div>
          <p class="hero-lead mb-2">
            A rare fusion where the science of the mind meets the healing of the soul.
            Earn <strong>dual trainings</strong> in Global NLP Practitioner Training
            and Universal Life Force Activation (Reiki). Live, practice-driven, and transformation-focused.
          </p>
          <ul class="mini-benefits">
            <li>Dual credentials (NLP + Reiki)</li>
            <li>Live, interactive cohorts on Zoom</li>
            <li>Practice, feedback & real-world protocols</li>
          </ul>
          <div class="hero-cta">
            <a href="#payment-block" class="btn btn-hero">Enroll now</a>
            <a href="#faqAccordion" class="btn btn-outline-hero">View FAQs</a>
          </div>
        </div>
        <div class="col-md-6">
          <?php if (!empty($landing_page['BANNER_IMAGE'])): ?>
            <div class="hero-image-wrap mt-3 mt-md-0">
              <img src="<?= htmlspecialchars($landing_page['BANNER_IMAGE']) ?>" alt="Program banner" class="hero-img"/>
              <!-- <div class="img-badge">IICT Approved</div> -->
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <!-- Main content + sidebar -->
    <div class="content">
      <!-- LEFT: Beautified program details -->

    
      <div class="main-content">

        <!-- Overview -->
        <div class="duo-hero">
          <div class="duo-kicker">The Destiny Duo  Bundle</div>
          <div class="duo-title">Where mind science meets energy healing</div>
          <p class="duo-sub mb-2">
            Two complete trainings — <strong>Global NLP Practitioner Training</strong> and
            <strong>Universal Life Force Activation (Reiki Level 1)</strong> — to rewire beliefs, master communication,
            and awaken your inner healer.
          </p>
          <div class="duo-badges">
            <span class="duo-badge">IICT-approved</span>
            <span class="duo-badge">Live Online via Zoom</span>
            <!-- <span class="duo-badge">Dual Credentials</span> -->
            <span class="duo-badge">Practice-Driven</span>
          </div>
        </div>

        <div class="duo-section">
          <div class="duo-h2">Why this? Why now?</div>
          <p class="duo-lead">
            Designed for working mums and purpose-led professionals who want results without the hustle-burnout cycle.
            If you’ve felt called to deeper tools for change, this is your bridge from knowing to <em>embodying</em> —
            from temporary motivation to lasting transformation.
          </p>
        </div>

        <div class="duo-section">
          <div class="duo-h2">What you’ll become</div>
          <ul class="duo-list">
            <li>Evidence-informed change-maker who shifts beliefs and emotional states rapidly and ethically.</li>
            <li>Energy-aligned practitioner restoring balance and resilience — within yourself first.</li>
            <li>Trusted professional elevating credibility and opportunity.</li>
          </ul>
        </div>

        <!-- At a glance -->
        <div class="duo-section duo-cards">
          <div class="row">
            <div class="col-md-6 mb-3">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <span>Global NLP Practitioner Training</span>
                  <span class="duo-tag">IICT-approved</span>
                </div>
                <div class="card-body">
                  <ul class="duo-list">
                    <li><strong>Core NLP models:</strong> outcomes, rapport, submodalities, anchoring, reframing, strategies & more.</li>
                    <li><strong>Language mastery:</strong> Meta Model, Milton Model, sleight-of-mouth, precision questioning.</li>
                    <li><strong>Applications:</strong> coaching, leadership, parenting, sales/marketing, wellbeing.</li>
                    <li><strong>Practicum:</strong> live demos, supervised practice, assessment & feedback.</li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-md-6 mb-3">
              <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <span>Universal Life Force Activation (Reiki Level 1)</span>
                  <!-- <span class="duo-tag">IICT-approved</span> -->
                </div>
                <div class="card-body">
                  <ul class="duo-list">
                    <li><strong>Foundations:</strong> attunement, self-healing protocols, energy hygiene, hands-on practice.</li>
                    <li><strong>Frameworks:</strong> Chakra balancing, grounding, protection, intuitive scanning, restoration.</li>
                    <li><strong>Ethical application:</strong> integrate Reiki safely in life & practice.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Integration -->
        <div class="duo-section">
          <div class="duo-h2">Integration Modules (the “rare fusion”)</div>
          <ul class="duo-list">
            <li><strong>Nervous-system informed coaching:</strong> pair state control (NLP) with energy regulation (Reiki).</li>
            <li><strong>Mind ↔ Energy session design:</strong> when to use which tool and how to combine them safely.</li>
            <li><strong>Client roadmaps:</strong> assessment to outcomes with measurable progress.</li>
          </ul>
        </div>

        <!-- How it ties -->
        <div class="duo-section">
          <div class="duo-h2">How it ties together — why the fusion works</div>
          <ul class="duo-list">
            <li><strong>NLP</strong> gives the structure of change — reframe meanings, update strategies, build new habits.</li>
            <li><strong>Reiki</strong> provides the state and capacity to integrate change at energetic and emotional levels.</li>
            <li><strong>Personalisation:</strong> align cognitive + energetic styles for truly individualised transformation.</li>
            <li><strong>Head-and-heart congruence:</strong> aligned thoughts, steady emotions, coherent energy, embodied results.</li>
          </ul>
        </div>

        <!-- Outcomes -->
        <div class="duo-section">
          <div class="duo-h2">Tangible outcomes you can expect</div>
          <ul class="duo-list">
            <li>Rewrite limiting beliefs and anchor empowering states on demand.</li>
            <li>Run confident client sessions with step-by-step protocols.</li>
            <li>Reduce stress & reactivity; improve sleep and focus.</li>
            <li>Communicate with clarity and compassion at home and work.</li>
            <li>Launch or upgrade a purposeful practice with dual-modality knowledge.</li>
          </ul>
        </div>

       <!-- Pricing summary -->
<div class="duo-section duo-pricing">
  <div class="duo-h2">Your investment (AUD)</div>

  <!-- Row 1: 2 individual courses + VIP Upgrade (moved here) -->
  <div class="row">
    <div class="col-md-4 mb-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="mb-1"><strong>Individual: Global NLP Practitioner Training</strong></div>
          <div class="price">AUD$2,214</div>
          <div class="small text-muted">Live Zoom delivery · IICT-approved</div>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="mb-1"><strong>Individual: Universal Life Force Activation (Reiki Level 1)</strong></div>
          <div class="price">AUD$888</div>
          <div class="small text-muted">Live Zoom delivery · Attunement + practice</div>
        </div>
      </div>
    </div>

    <!-- VIP moved to first row -->
    <div class="col-md-4 mb-3">
      <div class="card h-100">
        <div class="card-body">
          <div class="mb-1"><strong>VIP Upgrade (optional)</strong></div>
          <div class="small text-muted mb-2">Personalized mentorship, business tools, exclusive support</div>
          <a href="#contactForm" class="btn btn-outline-success btn-sm">Contact us</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Row 2: One unified bundle card -->
  <div class="row">
    <div class="col-12">
      <div class="card h-100 duo-bundle-card">
        <div class="card-body">
          <!-- Header -->
          <div class="bundle-head d-flex align-items-center justify-content-between flex-wrap">
            <div class="bundle-title">
              <strong>The Destiny Duo (bundle)</strong>
            </div>
            <span class="duo-save-badge">Save 50%</span>
          </div>

          <!-- Bundle price + savings -->
          <div class="bundle-price-wrap">
            <div class="bundle-price">AUD$1,551</div>
            <div class="bundle-savings text-muted">
              <span class="strike">AUD$3,102</span>
              <span class="ml-2">(save AUD$1,551)</span>
            </div>
            <div class="small text-muted mt-1">Full access to both trainings (use special coupon code)</div>
          </div>
<!-- Add Logos Section -->
<div class="bundle-logos text-center my-3">
  <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
    <img src="https://eyd.franhive.com/uploads/68c014b12dc43_33527331-787D-4C32-A917-2A5AF18ADBB0.jpeg" 
         alt="Logo 4" class="bundle-logo">
    <img src="https://eyd.franhive.com/uploads/68c014b12deec_B9F552BD-DADB-4C23-B1E6-2690A576877E_4_5005_c.jpeg" 
         alt="Logo 6" class="bundle-logo">
  </div>
</div>

          <hr class="my-3">

          <!-- Deposit / Balance side-by-side -->
          <div class="db-grid">
            <div class="db-item">
              <div class="db-kicker">Step 1</div>
              <div class="db-label">Deposit</div>
              <div class="db-amount">AUD$500</div>
              <div class="db-note text-muted">Secure your spot upfront</div>
            </div>

            <div class="db-item">
              <div class="db-kicker">Step 2</div>
              <div class="db-label">Balance (due within 2 weeks)</div>
              <div class="db-amount">AUD$1,051</div>
              <div class="db-note text-muted">Complete payment before training begins</div>
            </div>
          </div>

          <!-- CTA -->
          <div class="text-center mt-3">
            <a href="#payment-block" class="btn btn-duo">
              Go to secure checkout
            </a>
            <div class="small text-muted mt-2">SSL encrypted via Stripe • No hidden fees</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



       <!-- Meet Your Coach -->
       <div class="duo-section" id="coach">
  <div class="coach-title">Meet Your Trainer</div>

  <div class="coach-card">
    <!-- Row 1: Photo + short intro (side-by-side) -->
    <div class="row coach-intro align-items-start">
      <div class="col-md-4 mb-3 mb-md-0">
        <div class="photo-wrap">
          <img
            src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/DSC8051-1024x1536-1.jpg"
            alt="Barinderjeet Kaur — Empower Your Destiny"
            class="coach-img">
        </div>
      </div>

      <div class="col-md-8">
        <div class="coach-name">Barinderjeet Kaur</div>
        <div class="coach-sub">Founder, Empower Your Destiny · Human Behaviour Specialist</div>

        <!-- Updated short intro -->
        <p class="coach-text mb-2">
          Barinderjeet Kaur is the founder of Empower Your Destiny and a distinguished Human Behaviour Specialist with both a Master’s and an MPhil in Psychology. With more than a decade of corporate leadership experience, she blends academic rigour with transformative coaching to empower individuals and organisations worldwide.
        </p>
       
      </div>
    </div>

    <!-- Row 2: Full-width continuation + bullets + CTAs -->
    <div class="coach-details">
      <div class="row">
        <div class="col-12">
        <p class="coach-text mb-0">
          She is a Certified Coach &amp; Trainer, holds a Diploma in Hypnotherapy &amp; NLP, is a Reiki Master/Teacher, a Registered Hypnotherapist, and an Accredited Practitioner of Extended DISC. Since 2017, Barinderjeet has guided professionals and teams to cultivate self-leadership, confidence, clarity, and abundance—driving profound personal growth and organisational culture shifts.
        </p>
          <p class="coach-text mb-2">
            In recent years, she has expanded her mastery as a Trainer across advanced modalities, including:
          </p>

          <!-- Modalities -->
          <ul class="coach-list">
            <li>Neuro-Linguistic Programming (NLP)</li>
            <li>NLP Coaching</li>
            <li>Time Line Therapy®</li>
            <li>Hypnotherapy</li>
            <li>Reiki</li>
          </ul>

          <!-- Recognitions -->
          <!-- <p class="coach-text mb-2">
            She is internationally recognised under training boards such as:
          </p>
          <ul class="coach-list">
            <li>The American Board of NLP (ABNLP)</li>
            <li>The American Board of Hypnotherapy (ABH)</li>
            <li>Clinical Hypnosis Australia (CHA)</li>
            <li>The Time Line Therapy® Association (TLTA)</li>
          </ul> -->
          <p class="coach-text mb-0"> 
          She is internationally recognised under training boards such as the American Board of NLP (ABNLP), the American Board of Hypnotherapy (ABH), Clinical Hypnosis Australia (CHA), and the Time Line Therapy®️ Association (TLTA).
          </p>
          <!-- Provider statement -->
          <p class="coach-text mb-0">
            Through <em>Empower Your Destiny</em>, an IICT-aligned and Approved Training Provider, Barinderjeet delivers advanced Practitioner and Master Practitioner certifications. Each program is formally accredited, ensuring global recognition, professional competency, and the highest standards of ethical practice.
          </p>

          <!-- Contact options (unchanged) -->
          <div class="coach-cta">
            <a class="btn-msg" href="https://m.me/barinderjeet.kaur.39" target="_blank" rel="noopener" aria-label="Message on Facebook Messenger">
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 2.5C6.75 2.5 2.5 6.4 2.5 11.3c0 2.75 1.38 5.18 3.55 6.82v3.38l3.25-1.78c.88.25 1.81.38 2.78.38 5.25 0 9.5-3.9 9.5-8.8S17.25 2.5 12 2.5Z" stroke="#0084FF" stroke-width="1.5"/>
                <path d="M7 13.2l3.2-3 2.3 2 3.5-3" stroke="#0084FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
              <span>Message on <span class="dot">Messenger</span></span>
            </a>

            <a class="btn-wa" href="https://wa.me/61426886501?text=Hi%20Barinderjeet%2C%20I%27m%20interested%20in%20the%20Destiny%20Duo%20Certification." target="_blank" rel="noopener" aria-label="Chat on WhatsApp Business">
              <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 2.6a8.9 8.9 0 00-7.8 13.2l-1 3.7 3.8-1A8.9 8.9 0 1012 2.6Z" stroke="#25D366" stroke-width="1.5"/>
                <path d="M8.9 10.2c.1-.2.3-.3.5-.2l1.1.5c.2.1.3.3.2.5-.2.5 0 1.1.4 1.5l.4.4c.4.4 1 .6 1.5.4.2-.1.4 0 .5.2l.5 1.1c.1.2 0 .4-.2.5-1.1.6-2.5.4-3.5-.6l-.9-.9c-1-1-1.2-2.4-.5-3.4Z" fill="#25D366"/>
              </svg>
              <span>Chat on <span class="dot">WhatsApp</span></span>
            </a>
          </div>
        </div>
      </div>
    </div><!-- /coach-details -->
  </div>
</div>



        <!-- FAQ (collapsed by default) -->
        <div class="duo-section">
          <div class="duo-h2">FAQ — your questions, answered</div>
          <div id="faqAccordion" class="accordion">
            <!-- Repeatable FAQ card pattern -->
            <div class="card">
              <div class="card-header" id="faq-h1">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c1" aria-expanded="false" aria-controls="faq-c1">
                  Is this one combined course or two separate trainings?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c1" class="collapse" aria-labelledby="faq-h1" data-parent="#faqAccordion">
                <div class="card-body">Two separate trainings delivered within one journey.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h2">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c2" aria-expanded="false" aria-controls="faq-c2">
                  Are these trainings IICT-approved?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c2" class="collapse" aria-labelledby="faq-h2" data-parent="#faqAccordion">
                <div class="card-body">The Global NLP Practitioner Training is IICT‑approved (This enhances
professional credibility and access to practitioner insurance (where applicable) and
the Universal Life Force Activation Training is for yourself and only permits practice on
friends and family.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h3">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c3" aria-expanded="false" aria-controls="faq-c3">
                  Are there any prerequisites?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c3" class="collapse" aria-labelledby="faq-h3" data-parent="#faqAccordion">
                <div class="card-body">No prerequisites. This is open to anyone ready to grow, heal, and expand potential. Success comes from practice and integration.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h4">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c4" aria-expanded="false" aria-controls="faq-c4">
                  Who is this for?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c4" class="collapse" aria-labelledby="faq-h4" data-parent="#faqAccordion">
                <div class="card-body">Coaches, healers, leaders & entrepreneurs ready to align mind, energy, and results — from beginners to seasoned professionals.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h5">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c5" aria-expanded="false" aria-controls="faq-c5">
                  Which associations recognise EYD programs?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c5" class="collapse" aria-labelledby="faq-h5" data-parent="#faqAccordion">
                <div class="card-body">American Board of NLP · Time Line Therapy™ Association · American Board of NLP (Coaching Division) · Australian Hypnotherapy Association · International Institute for Complementary Therapy (IICT)
                .</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h6">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c6" aria-expanded="false" aria-controls="faq-c6">
                  Do I need full attendance to be certified?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c6" class="collapse" aria-labelledby="faq-h6" data-parent="#faqAccordion">
                <div class="card-body">Yes, full attendance is required for international certification. Catch-ups and support are available if you miss a small portion.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h7">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c7" aria-expanded="false" aria-controls="faq-c7">
                  What’s the mode of delivery?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c7" class="collapse" aria-labelledby="faq-h7" data-parent="#faqAccordion">
                <div class="card-body">LIVE online immersion via Zoom — interactive, practice-driven, with demos, breakout rooms and Q&A.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h8">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c8" aria-expanded="false" aria-controls="faq-c8">
                  Will I be ready to work with clients after?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c8" class="collapse" aria-labelledby="faq-h8" data-parent="#faqAccordion">
                <div class="card-body">Yes. If you complete the assessment and meet attendance requirements, you’ll
                  graduate as a certified Practitioner. You’ll practice live, learn ethical frameworks,
                  session structures, and receive ready-to-use client templates. Universal Life Force
                  Activation - Reiki I - only permits practice on friends and family</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h9">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c9" aria-expanded="false" aria-controls="faq-c9">
                  Dates & timings?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c9" class="collapse" aria-labelledby="faq-h9" data-parent="#faqAccordion">
                <div class="card-body">Intensive (8–9 consecutive days) or paced across weeks. Confirm your preferred intake before booking.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h10">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c10" aria-expanded="false" aria-controls="faq-c10">
                  Is there an exam?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c10" class="collapse" aria-labelledby="faq-h10" data-parent="#faqAccordion">
                <div class="card-body">For certification training programs - competency is assessed via demonstrated skills, reflective journaling, and a simple open-book test.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h11">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c11" aria-expanded="false" aria-controls="faq-c11">
                  Can I enroll in just one training now?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c11" class="collapse" aria-labelledby="faq-h11" data-parent="#faqAccordion">
                <div class="card-body">Yes - you absolutely can choose just one training certification. The Destiny Duo bundle, however, offers the best value and most integrated experience, which is why many students prefer it.</div>
              </div>
            </div>
            <div class="card">
              <div class="card-header" id="faq-h12">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c12" aria-expanded="false" aria-controls="faq-c12">
                  Pathways after this training?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c12" class="collapse" aria-labelledby="faq-h12" data-parent="#faqAccordion">
                <div class="card-body">Eligible for NLP Master Practitioner, Reiki Level 2 (and beyond). Ask about early-bird pricing and bonus bundles.</div>
              </div>
            </div>

            <div class="card">
              <div class="card-header" id="faq-h13">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c13" aria-expanded="false" aria-controls="faq-c13">
                  Age requirements?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c13" class="collapse" aria-labelledby="faq-h13" data-parent="#faqAccordion">
                <div class="card-body">No age limit. Participants under 18 need to be accompanied by an adult.</div>
              </div>
            </div>


            <div class="card">
              <div class="card-header" id="faq-h14">
                <button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#faq-c14" aria-expanded="false" aria-controls="faq-c14">
                What if I want to continue to higher levels after this training?
                  <span class="chev collapsed">⌄</span>
                </button>
              </div>
              <div id="faq-c14" class="collapse" aria-labelledby="faq-h14" data-parent="#faqAccordion">
                <div class="card-body">Many students feel called to go deeper - whether through NLP Master Practitioner
                                      Training or Reiki Level 2 (and beyond). By completing these trainings, you’ll be fully
                                      eligible to progress seamlessly into advanced certifications.

                                      If you know you’d like to continue your journey, you can also unlock special early bird
                                      pricing or exclusive bonus bundle options when booking your next level of training.
                                      Simply reach out to us if you feel called to go all in - we’ll guide you toward the best
                                      pathway that matches your vision and goals.</div>
              </div>
            </div>

            
          </div>
        </div>

      </div>

      <!-- RIGHT: Testimonials + right content (dynamic) -->
      <div class="side-content">
      <?php
  // Always show header if we have either videos or images
  $videos = [
    'https://youtube.com/shorts/fy8uaGOAXc4?si=gxg6GkqFuTcVt8Xn',
    'https://youtube.com/shorts/0nL-6tFSMZs?si=oQVI3IobIwE4x6Ic',
    'https://youtube.com/shorts/kRl9aP8l0rU?si=7xePIGXoA-dFYSvC',
    'https://youtube.com/shorts/nRYdVVxdRJs?si=AzmHxrjYbfgqVAxg',
    'https://youtube.com/shorts/sFOyUEpbP_Y?si=eyttll73IrMv2OyR',
  ];
  $hasImages = !empty($landing_page['TESTIMONIALS_IMAGES']);
  if ($hasImages || !empty($videos)):
?>
  <h2 class="text-center text-brandcolor">Testimonials</h2>
  <div class="testimonials-list">

    <?php
      // --- Videos first (YouTube Shorts → embed) ---
      foreach ($videos as $url):
        $url = trim($url);
        if (!$url) continue;
        $embed = '';
        if (preg_match('#/shorts/([^/?]+)#i', $url, $m)) {
          $embed = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (preg_match('#v=([^&]+)#i', $url, $m)) {
          $embed = 'https://www.youtube.com/embed/' . $m[1];
        }
        if ($embed):
    ?>
      <div class="testimonial-video">
        <iframe
          src="<?= htmlspecialchars($embed) ?>"
          title="Testimonial video"
          loading="lazy"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          referrerpolicy="strict-origin-when-cross-origin"></iframe>
      </div>
    <?php
        endif;
      endforeach;
    ?>

    <?php if ($hasImages): ?>
      <?php foreach (explode(',', $landing_page['TESTIMONIALS_IMAGES']) as $image): $image = trim($image); if(!$image) continue; ?>
        <div class="testimonial-image">
          <img src="<?= base_url().htmlspecialchars($image) ?>" class="img-fluid w-100" alt="Testimonial Image">
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php
      // --- Videos first (YouTube Shorts → embed) ---
      foreach ($videos as $url):
        $url = trim($url);
        if (!$url) continue;
        $embed = '';
        if (preg_match('#/shorts/([^/?]+)#i', $url, $m)) {
          $embed = 'https://www.youtube.com/embed/' . $m[1];
        } elseif (preg_match('#v=([^&]+)#i', $url, $m)) {
          $embed = 'https://www.youtube.com/embed/' . $m[1];
        }
        if ($embed):
    ?>
      <div class="testimonial-video">
        <iframe
          src="<?= htmlspecialchars($embed) ?>"
          title="Testimonial video"
          loading="lazy"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
          allowfullscreen
          referrerpolicy="strict-origin-when-cross-origin"></iframe>
      </div>
    <?php
        endif;
      endforeach;
    ?>

  </div>
<?php endif; ?>

        <?= $landing_page['LANDING_PAGE_RIGHT_CONTENT']; ?>
      </div>
    </div>

    <div class="contact-form" id="register-block">
  <!-- Status messages (kept for existing AJAX) -->
  
 
  <div class="regcard">
    <!-- Header -->
    <div class="reg-head">
      <div class="reg-icon">
        <!-- person icon -->
        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" aria-hidden="true">
          <circle cx="12" cy="8" r="4" fill="#10b981"/>
          <path d="M4 20c0-4 4-6 8-6s8 2 8 6" fill="#10b981"/>
        </svg>
      </div>
      <div>
        <h5 mb-0>Register your interest</h5>
        <div class="muted">We’ll reach out with onboarding details and next steps.</div>
      </div>
    </div>
    <div id="contact-status"></div>
    <!-- Body -->
      <div class="reg-body">
        <form id="contactForm">
          <!-- Hidden field for EMAIL_TEMPLATE (unchanged) -->
          <input type="hidden" name="EMAIL_TEMPLATE"
                value="<?= isset($landing_page['EMAIL_TEMPLATE']) ? htmlspecialchars($landing_page['EMAIL_TEMPLATE']) : '' ?>">

          <!-- Name + Mobile (side-by-side on md+, stacked on mobile) -->
          <div class="form-row-gap">
            <div class="form-group mb-2">
              <label for="name">Full name</label>
              <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Jane Doe">
            </div>
            <div class="form-group mb-2">
              <label for="mobile">Mobile</label>
              <input type="text" class="form-control" id="mobile" name="mobile" required placeholder="e.g. +61 4xx xxx xxx">
            </div>
          </div>

          <!-- Email -->
          <div class="form-group mb-2">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required placeholder="you@domain.com">
            <div class="hint">We’ll send your confirmation and joining details here.</div>
          </div>

          <!-- Platform -->
          <div class="form-group mb-2">
            <label for="platform">Preferred platform to stay in touch</label>
            <select class="form-control" id="platform" name="platform" required>
              <option value="" disabled selected>Select an option</option>
              <option value="1">WhatsApp</option>
              <option value="2">Messenger</option>
              <option value="3">Email</option>
            </select>
          </div>

          <!-- Privacy note -->
          <div class="privacy">
            Your details are kept private and used only to contact you about this training.
          </div>

          <!-- Actions (keeps your PHP button text logic) -->
          <div class="actions">
            <?php if ($landing_page['LANDING_PAGE_ID'] == 20) { ?>
              <button type="submit" class="btn-reg">
                YES! I Want to Activate My Relationships <span class="chev">›</span>
              </button>
            <?php } else { ?>
              <button type="submit" class="btn-reg">
                Submit <span class="chev">›</span>
              </button>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>




        <hr class="my-4">

        <div id="payment-block" class="mt-4 theme-green with-halo">
          <div class="card pcard">
            <div class="pcard-head">
              <div class="icon">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none">
                  <rect x="3" y="10" width="18" height="10" rx="2" stroke="#10b981" stroke-width="2" fill="white"/>
                  <path d="M7 10V8a5 5 0 0 1 10 0v2" stroke="#10b981" stroke-width="2"/>
                </svg>
              </div>
              <div>
                <div class="h5 mb-0">Purchase the Course</div>
                <div class="muted">Secure checkout via Stripe</div>
              </div>
            </div>

            <div class="pcard-body">
              <!-- Item rows -->
              <div class="rowline">
                <div>Individual: <strong>Global NLP Practitioner Training</strong></div>
                <div><strong id="item-nlp" data-amount-cents="221400" data-currency="aud">AUD$2,214.00</strong></div>
              </div>
              <div class="rowline">
                <div>Individual: <strong>Universal Life Force Activation (Reiki Level 1)</strong></div>
                <div><strong id="item-reiki" data-amount-cents="88800" data-currency="aud">AUD$888.00</strong></div>
              </div>

              <!-- Discount (hidden until coupon is applied) -->
              <div class="rowline discount-row d-none" id="discount-row">
                <div>Discount</div>
                <div><strong id="discount-amount">−AUD$0.00</strong></div>
              </div>

              <!-- Total -->
              <div class="rowline" style="border-top:1px solid #eef2f7">
                <div class="text-uppercase muted" style="letter-spacing:.04em">Total</div>
                <div><strong id="total-price">AUD$3,102.00</strong></div>
              </div>

              <!-- Coupon -->
              <div class="coupon-wrap">
                <label class="sr-only" for="coupon">Coupon</label>
                <input type="text" class="form-control" id="coupon" placeholder="Coupon (e.g. DUOCOPONCODE)">
                <button id="apply-coupon" class="btn btn-apply" type="button">Apply Coupon</button>
              </div>

              <!-- Promo nudge -->
              <!-- <div id="coupon-nudge" class="promo-nudge">
                <div class="burst" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none">
                    <path d="M12 2l2.6 5.5 6 .5-4.6 4 1.4 5.8L12 15.9 6.6 17.8 8 12 3.4 8l6-.5L12 2z" fill="#ffffff"/>
                  </svg>
                </div>
                <div>
                  <span class="highlight">Save up to 50% today</span> — apply your coupon for instant savings.
                  <div class="sub small">Limited-time offer • Discounts shown before you pay</div>
                </div>
              </div> -->

              <div id="coupon-nudge" class="promo-nudge emphasis" role="note" aria-live="polite">
  <div class="burst" aria-hidden="true">
    <svg viewBox="0 0 24 24" fill="none"><path d="M12 2l2.6 5.5 6 .5-4.6 4 1.4 5.8L12 15.9 6.6 17.8 8 12 3.4 8l6-.5L12 2z" fill="#ffffff"/></svg>
  </div>
  <div>
    <div class="headline">Bundle both courses & save up to 50%</div>
    <div class="sub">
      Get the <strong>Global NLP Practitioner</strong> + <strong>Reiki Level 1</strong> together for
      <span class="price-pill pop"> Only AUD$1,551.00</span>  when you apply your bundle coupon — the discount is shown before you pay.
      <a href="#coupon" id="apply-bundle" class="cta-link">Apply now</a>
    </div>
  </div>
</div>


              <div id="coupon-feedback" class="coupon-help"></div>

              <!-- Trust + methods -->
              <div class="secure">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none">
                  <path d="M4 12l5 5L20 6" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span>SSL encrypted • No hidden fees • Instant email receipt</span>
              </div>
              <div class="badges">
                <div class="badge-pay">Visa</div>
                <div class="badge-pay">Mastercard</div>
                <div class="badge-pay">Amex</div>
                <div class="badge-pay">UPI</div>
              </div>

              <!-- Actions (FULL-WIDTH button, centered prices) -->
              <div class="btn-row">
                <div class="pay-grid">
                  <!-- Bank Transfer -->
                  <button id="pay-bank" class="btn btn-bank" type="button" aria-live="polite">
                    <div class="btn-title" id="bank-pay-label">Direct Bank Transfer — AUD$500 deposit</div>
                    <div class="cta-price">
                      <span class="pill-amount" id="bank-pay-deposit">AUD$500.00</span>
                      <span class="sep">•</span>
                      <span class="muted small">
                        Remaining <strong id="bank-pay-remaining">AUD$1,051.00</strong> later <span id="bank-pay-remaining-text"> (** Apply your coupon to get 50% off)</span>
                      </span>
                    </div>
                  </button>

                  <!-- Stripe (unchanged id so your JS continues to work) -->
                  <button id="pay-now" class="btn btn-accent" type="button" aria-live="polite">
                    <div class="btn-title" id="pay-now-label">Secure Online Checkout AUD$500 deposit</div>
                    <div class="cta-price">
                      <span class="pill-amount" id="pay-now-deposit">AUD$500.00</span>
                      <span class="sep">•</span>
                      <span class="muted small">
                        Remaining <strong id="pay-now-remaining">AUD$1,051.00</strong> later
                      </span>
                    
                    </div>
                    <span id="bank-pay-remaining-text"> (** Apply your coupon to get 50% off)</span>
                  </button>
                </div>
              </div>

              <!-- Bank Transfer Modal -->
              <div class="modal fade" id="bankModal" tabindex="-1" role="dialog" aria-labelledby="bankModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="bankModalTitle">Get bank transfer details</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <p class="small text-muted mb-2">We’ll email you instructions to pay the deposit by bank transfer.</p>

                      <div class="d-flex justify-content-between align-items-center bg-light rounded p-2 mb-3">
                        <div>
                          <div class="small text-uppercase text-muted">Amount now</div>
                          <div class="font-weight-bold" id="bank-modal-now">AUD$500.00</div>
                        </div>
                        <div class="text-muted">•</div>
                        <div>
                          <div class="small text-uppercase text-muted">Balance later</div>
                          <div class="font-weight-bold" id="bank-modal-later">AUD$1,051.00</div>
                        </div>
                      </div>

                      <div class="form-group mb-1">
                        <label for="bankEmail" class="small mb-1">Your email</label>
                        <input type="email" class="form-control" id="bankEmail" placeholder="you@example.com" required>
                        <div class="invalid-feedback">Please enter a valid email address.</div>
                      </div>
                      <div id="bankModalStatus" class="mt-2 small"></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                      <button type="button" id="bank-send-confirm" class="btn btn-brand">Email me the details</button>
                    </div>
                  </div>
                </div>
              </div>



              <!-- Hidden fields used by JS -->
              <input type="hidden" id="promotion-code-id" value="">
              <input type="hidden" id="applied-coupon" value="">
            </div>
          </div>
        </div>

        <!-- Help / Connect -->

<section id="help-connect" class="mt-4">
  <div class="connect-card">
    <div class="connect-title">Still have questions?</div>
    <div class="connect-sub">Ask us directly — we reply fast. Choose an option:</div>

    <div class="connect-cta">
       <!-- WhatsApp: Barinder -->
       <a class="chip btn-wa" id="wa-barinder" href="https://wa.me/61426886501?text=Hi%20Barinderjeet%2C%20I%27m%20interested%20in%20the%20Destiny%20Duo%20Certification." target="_blank" rel="noopener"
         aria-label="Chat with Barinder on WhatsApp">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M12 2.6a8.9 8.9 0 00-7.8 13.2l-1 3.7 3.8-1A8.9 8.9 0 1012 2.6Z" stroke="#25D366" stroke-width="1.5"/>
          <path d="M8.9 10.2c.1-.2.3-.3.5-.2l1.1.5c.2.1.3.3.2.5-.2.5 0 1.1.4 1.5l.4.4c.4.4 1 .6 1.5.4.2-.1.4 0 .5.2l.5 1.1c.1.2 0 .4-.2.5-1.1.6-2.5.4-3.5-.6l-.9-.9c-1-1-1.2-2.4-.5-3.4Z" fill="#25D366"/>
        </svg>
        <span>WhatsApp <span class="dot-wa">Messenger</span></span>
      </a>
      <!-- Messenger: EYD Team -->
      <a class="chip btn-msg" href="https://m.me/empoweryourdestiny" target="_blank" rel="noopener"
         aria-label="Message EYD Team on Facebook Messenger">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M12 2.5C6.7 2.5 2.5 6.4 2.5 11.3c0 2.8 1.4 5.2 3.5 6.8v3.4l3.3-1.8c.9.3 1.8.4 2.7.4 5.3 0 9.5-3.9 9.5-8.8S17.2 2.5 12 2.5Z" stroke="#0084FF" stroke-width="1.5"/>
          <path d="M7 13.2l3.2-3 2.3 2 3.5-3" stroke="#0084FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Facebook <span class="dot-msg"> Page Messenger</span></span>
      </a>

      <!-- Messenger: Barinder -->
      <a class="chip btn-msg" href="https://m.me/barinderjeet.kaur.39" target="_blank" rel="noopener"
         aria-label="Message Barinder on Facebook Messenger">
        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
          <path d="M12 2.5C6.7 2.5 2.5 6.4 2.5 11.3c0 2.8 1.4 5.2 3.5 6.8v3.4l3.3-1.8c.9.3 1.8.4 2.7.4 5.3 0 9.5-3.9 9.5-8.8S17.2 2.5 12 2.5Z" stroke="#0084FF" stroke-width="1.5"/>
          <path d="M7 13.2l3.2-3 2.3 2 3.5-3" stroke="#0084FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <span>Private Messenger <span class="dot-msg"> Chat with Your Trainer</span></span>
      </a>

     
    </div>
  </div>

  <!-- Toast for Messenger copy helper -->
  <div id="connect-copy-toast" role="status" aria-live="polite">Intro copied — paste in Messenger ✅</div>
</section>


        <!-- Stripe.js (place once per page, near other scripts) -->
        <script src="https://js.stripe.com/v3/"></script>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <!-- Adjust Side Content Height Script -->
   <!-- Stripe / Payment block logic -->
<script>
  // ---- globals used across functions ----
  // ---- globals used across functions ----
const DEPOSIT_CENTS       = 50000;   // AUD$500 deposit
const BUNDLE_TOTAL_CENTS  = 155100;  // AUD$1,551.00 (bundle after coupon)
const locale              = 'en-AU';
const currency            = 'aud';

// Force "AUD$" prefix no matter the browser
function moneyAU(cents){
  return 'AUD$' + new Intl.NumberFormat(locale, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format((cents || 0) / 100);
}

  // Update CTA (deposit vs full) + show deposit/remaining on the button
// Ensure both pills show AUD$ formatting on load (you already have DEPOSIT_CENTS etc.)
$("#pay-now-deposit").text(moneyAU(DEPOSIT_CENTS));
$("#bank-pay-deposit").text(moneyAU(DEPOSIT_CENTS));

// Extend your updatePayCta(totalCents) to also update the bank-transfer UI
function updatePayCta(totalCents) {
  const $btn   = $("#pay-now");
  const $label = $("#pay-now-label");
  const $dep   = $("#pay-now-deposit");
  const $rem   = $("#pay-now-remaining");

  // existing logic for Stripe button (deposit vs full)
  if (totalCents === BUNDLE_TOTAL_CENTS) {
    const remaining = Math.max(0, totalCents - DEPOSIT_CENTS);
    $btn.addClass("is-deposit").data("mode","deposit");
    $label.text("Secure Online Checkout AUD$500 deposit");
    $dep.text(moneyAU(DEPOSIT_CENTS));
    $rem.text(moneyAU(remaining));
  } else {
    $btn.removeClass("is-deposit").data("mode","full");
    $label.text("Secure Online Checkout " + moneyAU(totalCents));
  }

  // --- NEW: mirror amounts for the bank transfer button ---
  const bankRemaining = Math.max(0, totalCents - DEPOSIT_CENTS);
  $("#bank-pay-deposit").text(moneyAU(DEPOSIT_CENTS));
  $("#bank-pay-remaining").text(moneyAU(bankRemaining));
  $("#bank-amount-now").text(moneyAU(DEPOSIT_CENTS));
  $("#bank-amount-later").text(moneyAU(bankRemaining));

  if (totalCents === BUNDLE_TOTAL_CENTS) {
    $("#bank-pay-label").text("Direct Bank Transfer — AUD$500 deposit");
  } else {
    $("#bank-pay-label").text("Direct Bank Transfer — Pay " + moneyAU(totalCents));
  }
}

// // Show bank instructions on click (no change to Stripe flow)
// $("#pay-bank").on("click", function () {
//   $("#bank-transfer-info").removeClass("d-none").hide().slideDown(200);
//   document.getElementById("bank-transfer-info").scrollIntoView({ behavior: "smooth", block: "start" });
// });



  $(function() {
    // ---- DOM caches ----
    const $nlp            = $("#item-nlp");
    const $reiki          = $("#item-reiki");
    const $total          = $("#total-price");
    const $coupon         = $("#coupon");
    const $couponFeedback = $("#coupon-feedback");
    const $promoId        = $("#promotion-code-id");
    const $applyBtn       = $("#apply-coupon");
    const $payBtn         = $("#pay-now");

    // ---- helpers ----
    function centsFrom($el){ return parseInt($el.data("amount-cents"), 10) || 0; }
    function baseTotalCents(){ return centsFrom($nlp) + centsFrom($reiki); }
    function money(cents){ return moneyAU(cents); }

    function setTotal(c) {
      const formatted = money(c);
      $total.text(formatted);

      if (typeof updateRemainingDisplays === "function") updateRemainingDisplays(c);
      updatePayCta(c);

      // optional strike-through/original UI (only if those spans exist)
      const base = baseTotalCents();
      const $orig = $("#pay-now-amount-orig");
      const $final = $("#pay-now-amount-final");
      if ($orig.length && $final.length) {
        if (c < base) {
          $orig.removeClass("d-none").text(money(base));
        } else {
          $orig.addClass("d-none").text(money(base));
        }
        $final.text(formatted);
      }
    }

    // Discount row helpers
    function showDiscount(offCents){
      $("#discount-row").removeClass("d-none");
      $("#discount-amount").text("−" + money(offCents));
    }
    function hideDiscount(){
      $("#discount-row").addClass("d-none");
      $("#discount-amount").text("−" + money(0));
    }

    // Initialize totals + CTA once
    setTotal(baseTotalCents());

    // ---- Apply coupon ----
    $applyBtn.on("click", function() {
      const code = ($coupon.val() || "").trim();
      const amountCents = baseTotalCents();
      if (!code){
        $coupon.addClass("is-invalid");
        $couponFeedback.html('<span class="text-danger">Please enter a coupon code.</span>');
        $("#coupon-nudge").removeClass("d-none");
        return;
      }
      $coupon.removeClass("is-invalid");
      $couponFeedback.empty();

      const orig = $applyBtn.text();
      $applyBtn.prop("disabled", true).text("Applying…");

      $.ajax({
        url: "<?= base_url('/PaymentController/validate_promo') ?>",
        type: "POST",
        dataType: "json",
        data: { code: code, amount_cents: amountCents, currency: currency }
      })
      .done(function(resp){
        if (resp && resp.status === "success") {
          setTotal(resp.data.total_cents);
          $promoId.val(resp.data.promotion_code_id || "");
          $couponFeedback.html('<span class="text-success">Coupon applied: ' +
                               resp.data.code + ' (−' + money(resp.data.discount_cents) + ')</span>');
          showDiscount(resp.data.discount_cents);
          $("#coupon-nudge").addClass("d-none");
        } else {
          setTotal(baseTotalCents());
          $promoId.val("");
          hideDiscount();
          $coupon.addClass("is-invalid");
          $couponFeedback.html('<span class="text-danger">' +
                               ((resp && resp.message) ? resp.message : "Invalid or expired coupon") +
                               '</span>');
          $("#coupon-nudge").removeClass("d-none");
        }
      })
      .fail(function(xhr){
        setTotal(amountCents);
        $promoId.val("");
        hideDiscount();
        let msg = 'Could not validate coupon. Please try again.';
        try { const j = JSON.parse(xhr.responseText||''); if (j.message) msg = j.message; } catch(e){}
        $coupon.addClass("is-invalid");
        $couponFeedback.html('<span class="text-danger">'+msg+'</span>');
        $("#coupon-nudge").removeClass("d-none");
      })
      .always(function(){ $applyBtn.prop("disabled", false).text(orig); });
    });

    // ---- Pay Now (routes to deposit OR full based on CTA mode) ----
    $("#pay-now").off("click.eyd").on("click.eyd", function () {
      const mode            = ($payBtn.data("mode") || "full").toString(); // "deposit" or "full"
      const isDepositMode   = (mode === "deposit");
      const promotionCodeId = $("#promotion-code-id").val() || "";

      // recompute current total (items minus visible discount)
      const totalCents = (function(){
        const nlp   = centsFrom($nlp);
        const reiki = centsFrom($reiki);
        let discount = 0;
        if (!$("#discount-row").hasClass("d-none")) {
          const txt = ($("#discount-amount").text() || "").replace(/[^\d.]/g,'');
          if (txt) discount = Math.round(parseFloat(txt) * 100);
        }
        return Math.max(0, nlp + reiki - discount);
      })();

      // Optional plan controls (exist only if you added them)
      const planMode    = $('input[name="plan_mode"]').length ? ($('input[name="plan_mode"]:checked').val() || 'auto') : 'auto';
      const balanceDate = $("#balance-date").length ? ($("#balance-date").val() || "") : "";

      const endpoint = isDepositMode
      ? "<?= base_url('/PaymentController/create_checkout_session_deposit') ?>"
      : "<?= base_url('/PaymentController/create_checkout_session') ?>";


      const payload = isDepositMode ? {
        deposit_cents:     DEPOSIT_CENTS,          // charge AUD$500 now
        total_cents:       totalCents,             // discounted total → remainder
        currency:          currency,
        landing_page_id:   "<?= (int)$landing_page['LANDING_PAGE_ID']; ?>",
        product_name:      "<?= htmlspecialchars($landing_page['LANDING_PAGE_NAME']); ?>",
        promotion_code_id: promotionCodeId,        // keep in metadata
        plan_mode:         planMode,               // 'auto' | 'manual'
        balance_due_date:  balanceDate
      } : {
        amount_cents:      totalCents,             // full charge now
        currency:          currency,
        landing_page_id:   "<?= (int)$landing_page['LANDING_PAGE_ID']; ?>",
        product_name:      "<?= htmlspecialchars($landing_page['LANDING_PAGE_NAME']); ?>",
        promotion_code_id: promotionCodeId
      };

      const $title = $("#pay-now-label").length ? $("#pay-now-label") : $payBtn;
      const orig   = $title.text();
      $payBtn.prop("disabled", true); $title.text("Redirecting…");

      $.ajax({ url: endpoint, type: "POST", dataType: "json", data: payload })
        .done(function(resp){
          if (resp && resp.status === "success") {
            const stripe = Stripe("<?= STRIPE_PUBLISHABLE_KEY ?>");
            stripe.redirectToCheckout({ sessionId: resp.data.session_id });
          } else {
            alert((resp && resp.message) ? resp.message : "Unable to start checkout.");
            $payBtn.prop("disabled", false); $title.text(orig);
          }
        })
        .fail(function(xhr, status){
          // Show real backend error to debug quickly
          let body = xhr.responseText || "";
          let msg  = "Checkout failed.";
          try { const j = JSON.parse(body); if (j.message) msg = j.message; } catch(e) {}
          alert("HTTP " + xhr.status + " — " + status + "\n" + msg + (body && msg=== "Checkout failed." ? ("\n\n" + body) : ""));
          $payBtn.prop("disabled", false); $title.text(orig);
        });
    });

  });
</script>

<script>
$(function () {
  const $form   = $("#contactForm");
  const $status = $("#contact-status");
  const $button = $("#contactForm button[type='submit']");
  const btnTxt  = $button.text();

  // Helper: update CSRF token if API returns a new one (CI3 friendly)
  function refreshCsrf(resp) {
    if (resp && resp.csrf_token_name && resp.csrf_hash) {
      // If you keep a hidden CSRF field in the form, update it:
      const name = resp.csrf_token_name;
      const hash = resp.csrf_hash;
      let $hidden = $form.find("input[name='"+name+"']");
      if (!$hidden.length) {
        $hidden = $("<input>", {type:"hidden", name:name}).appendTo($form);
      }
      $hidden.val(hash);
    }
  }

  $form.on("submit", function (e) {
    e.preventDefault();

    $status
      .removeClass("alert alert-success alert-danger")
      .empty();

    $button.prop("disabled", true).text("Submitting…");

    $.ajax({
      url: "<?= base_url('LandingPageController/submit_contact') ?>",
      type: "POST",
      dataType: "json",
      data: $form.serialize()
    })
    .done(function (resp) {
      refreshCsrf(resp);

      if (resp && resp.status === "success") {
        // Show your thanks content from DB and hide the form
        $status
          .addClass("alert alert-success")
          .html(<?= json_encode($landing_page['LANDING_PAGE_THANKS_CONTENT']); ?>);
        $form.slideUp(200);
      } else {
        const msg = (resp && resp.message) ? resp.message : "Something went wrong. Please try again.";
        $status
          .addClass("alert alert-danger")
          .text(msg);
        $button.prop("disabled", false).text(btnTxt);
      }
    })
    .fail(function (xhr) {
      let msg = "An error occurred. Please try again.";
      try {
        const j = JSON.parse(xhr.responseText || "");
        if (j && j.message) msg = j.message;
      } catch(e){}
      $status
        .addClass("alert alert-danger")
        .text(msg);
      $button.prop("disabled", false).text(btnTxt);
    });
  });
});
</script>
<script>
(function () {
  // 1) Keep a reliable running total for the modal + CTAs
  window.currentTotalCents =
    (typeof window.currentTotalCents !== "undefined")
      ? window.currentTotalCents
      : (typeof baseTotalCents === "function" ? baseTotalCents() : 155100); // AUD$1,551.00

  // 2) Seed the deposit pills
  if (typeof moneyAU === "function") {
    $("#pay-now-deposit, #bank-pay-deposit").text(moneyAU(DEPOSIT_CENTS));
  } else {
    $("#pay-now-deposit, #bank-pay-deposit").text("AUD$" + (DEPOSIT_CENTS/100).toFixed(2));
  }

  // 3) Extend updatePayCta so the Bank button + modal stay in sync
  if (typeof updatePayCta === "function") {
    const prevUpdate = updatePayCta;
    window.updatePayCta = function (totalCents) {
      window.currentTotalCents = totalCents;

      // keep existing Stripe button logic
      prevUpdate(totalCents);

      const remaining = Math.max(0, totalCents - DEPOSIT_CENTS);

      // mirror to Bank button
      if (typeof moneyAU === "function") {
        $("#bank-pay-deposit").text(moneyAU(DEPOSIT_CENTS));
        $("#bank-pay-remaining").text(moneyAU(remaining));
        $("#bank-modal-now").text(moneyAU(DEPOSIT_CENTS));
        $("#bank-modal-later").text(moneyAU(remaining));
      } else {
        $("#bank-pay-deposit").text("AUD$" + (DEPOSIT_CENTS/100).toFixed(2));
        $("#bank-pay-remaining").text("AUD$" + (remaining/100).toFixed(2));
        $("#bank-modal-now").text("AUD$" + (DEPOSIT_CENTS/100).toFixed(2));
        $("#bank-modal-later").text("AUD$" + (remaining/100).toFixed(2));
      }

      $("#bank-pay-label").text(
        totalCents === BUNDLE_TOTAL_CENTS
          ? "Bank transfer — AUD$500 deposit"
          : "Bank transfer — Pay " + (typeof moneyAU === "function" ? moneyAU(totalCents) : ("AUD$" + (totalCents/100).toFixed(2)))
      );
    };

    // initialize once
    window.updatePayCta(window.currentTotalCents);
  }

  // 4) Open modal on Bank Transfer (no old scrollIntoView)
  $("#pay-bank").off("click").on("click", function () {
    const prefill = ($("#email").val() || "").trim();
    $("#bankEmail").val(prefill).removeClass("is-invalid");
    $("#bankModalStatus").removeClass("text-success text-danger").empty();

    const total = window.currentTotalCents;
    const remaining = Math.max(0, total - DEPOSIT_CENTS);

    if (typeof moneyAU === "function") {
      $("#bank-modal-now").text(moneyAU(DEPOSIT_CENTS));
      $("#bank-modal-later").text(moneyAU(remaining));
    } else {
      $("#bank-modal-now").text("AUD$" + (DEPOSIT_CENTS/100).toFixed(2));
      $("#bank-modal-later").text("AUD$" + (remaining/100).toFixed(2));
    }

    $("#bankModal").modal("show");
  });

  // 5) Send email on confirm (bound ONCE)
  $("#bank-send-confirm").off("click").on("click", function () {
    const email = ($("#bankEmail").val() || "").trim();
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      $("#bankEmail").addClass("is-invalid");
      return;
    }
    $("#bankEmail").removeClass("is-invalid");

    const $btn = $(this);
    const orig = $btn.text();
    $btn.prop("disabled", true).text("Sending…");

    const total = window.currentTotalCents || BUNDLE_TOTAL_CENTS;
    const remaining = Math.max(0, total - DEPOSIT_CENTS);

    $.ajax({
      url: "<?= base_url('/PaymentController/send_bank_details_email') ?>",
      type: "POST",
      dataType: "json",
      data: {
        email: email,
        deposit_cents: DEPOSIT_CENTS,
        total_cents: total,
        remaining_cents: remaining,
        currency: 'aud',
        landing_page_id: "<?= (int)$landing_page['LANDING_PAGE_ID']; ?>",
        product_name: "<?= htmlspecialchars($landing_page['LANDING_PAGE_NAME']); ?>"
        // include CSRF if enabled
      }
    })
    .done(function (resp) {
      if (resp && resp.status === "success") {
        $("#bankModalStatus").removeClass("text-danger").addClass("text-success")
          .text("We’ve emailed you the bank transfer details. Please check your inbox (and spam).");
        setTimeout(function () { $("#bankModal").modal("hide"); }, 1200);
      } else {
        const msg = (resp && resp.message) ? resp.message : "Could not send email. Please try again.";
        $("#bankModalStatus").removeClass("text-success").addClass("text-danger").text(msg);
      }
    })
    .fail(function (xhr) {
      let msg = "Could not send email. Please try again.";
      try { const j = JSON.parse(xhr.responseText || ""); if (j.message) msg = j.message; } catch(e){}
      $("#bankModalStatus").removeClass("text-success").addClass("text-danger").text(msg);
    })
    .always(function () { $btn.prop("disabled", false).text(orig); });
  });

})();
</script>


</body>
</html>
