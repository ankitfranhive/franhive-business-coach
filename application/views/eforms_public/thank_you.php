<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php
    if (!empty($thank_you_template['title'])) {
      echo html_escape($thank_you_template['title']) . ' | Empower Your Destiny';
    } else {
      echo 'Thank you for registering | Empower Your Destiny';
    }
  ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand: #ffc107;
      --brand-dark: #e6a800;
      --ink: #1a1c1e;
      --muted: #5c636a;
      --line: rgba(26, 28, 30, 0.10);
      --surface: #ffffff;
      --whatsapp: #25d366;
      --whatsapp-dark: #1ebe57;
      --zoom: #2d8cff;
      --zoom-dark: #0e72ed;
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      min-height: 100vh;
      font-family: "Source Sans 3", system-ui, sans-serif;
      color: var(--ink);
      background:
        radial-gradient(ellipse 80% 50% at 10% -10%, rgba(255, 193, 7, 0.28), transparent 55%),
        radial-gradient(ellipse 60% 40% at 100% 0%, rgba(230, 168, 0, 0.18), transparent 50%),
        linear-gradient(165deg, #f7f1e3 0%, #efe6d2 45%, #f4efe4 100%);
      display: flex;
      flex-direction: column;
    }

    .topbar {
      background: rgba(255, 255, 255, 0.88);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid var(--line);
    }

    .topbar-inner {
      max-width: 720px;
      margin: 0 auto;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .logo {
      height: 44px;
      width: auto;
      display: block;
    }

    .brand-name {
      font-family: "Fraunces", Georgia, serif;
      font-weight: 700;
      font-size: 1.05rem;
      letter-spacing: -0.01em;
    }

    .main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 28px 16px 40px;
    }

    .panel {
      width: 100%;
      max-width: 640px;
      background: var(--surface);
      border: 1px solid var(--line);
      border-radius: 20px;
      box-shadow:
        0 1px 2px rgba(26, 28, 30, 0.04),
        0 18px 48px rgba(26, 28, 30, 0.08);
      overflow: hidden;
      opacity: 0;
      transform: translateY(18px);
      animation: rise-in 0.7s cubic-bezier(0.22, 1, 0.36, 1) 0.08s forwards;
    }

    .accent {
      height: 8px;
      background: linear-gradient(90deg, var(--brand), var(--brand-dark), #f0d078);
      background-size: 200% 100%;
      animation: shimmer 4s ease-in-out infinite;
    }

    .panel-body {
      padding: 36px 36px 32px;
      text-align: center;
    }

    .icon-wrap {
      width: 72px;
      height: 72px;
      margin: 0 auto 20px;
      border-radius: 50%;
      background: linear-gradient(145deg, #fff8e1, #ffe082);
      border: 1px solid rgba(230, 168, 0, 0.35);
      display: grid;
      place-items: center;
      animation: soft-pulse 2.4s ease-in-out infinite;
    }

    .icon-wrap svg {
      width: 34px;
      height: 34px;
      color: #8a6400;
    }

    h1 {
      font-family: "Fraunces", Georgia, serif;
      font-size: clamp(1.65rem, 4vw, 2.15rem);
      font-weight: 700;
      line-height: 1.2;
      letter-spacing: -0.02em;
      margin: 0 0 10px;
      opacity: 0;
      animation: fade-up 0.65s ease 0.25s forwards;
    }

    .heart {
      display: inline-block;
      animation: heart-beat 1.6s ease-in-out 0.9s infinite;
      transform-origin: center;
    }

    .lead {
      margin: 0 auto 22px;
      max-width: 34em;
      font-size: 1.05rem;
      line-height: 1.55;
      color: var(--muted);
      opacity: 0;
      animation: fade-up 0.65s ease 0.38s forwards;
    }

    .copy {
      text-align: left;
      margin: 0 auto 26px;
      max-width: 36em;
      display: flex;
      flex-direction: column;
      gap: 14px;
      opacity: 0;
      animation: fade-up 0.65s ease 0.5s forwards;
    }

    .copy p {
      margin: 0;
      font-size: 0.98rem;
      line-height: 1.6;
      color: #3a3f44;
    }

    .copy strong {
      color: var(--ink);
      font-weight: 600;
    }

    .email-chip {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 2px;
      padding: 4px 10px;
      border-radius: 999px;
      background: #fff8e1;
      border: 1px solid #ffe082;
      color: #7a5800;
      font-size: 0.9rem;
      font-weight: 600;
      word-break: break-all;
    }

    .cta-block {
      border-top: 1px solid var(--line);
      padding-top: 22px;
      opacity: 0;
      animation: fade-up 0.65s ease 0.62s forwards;
    }

    .cta-block p {
      margin: 0 0 14px;
      font-size: 0.98rem;
      line-height: 1.55;
      color: var(--muted);
    }

    .next-steps {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .step-card {
      text-align: left;
      padding: 16px 16px 18px;
      border-radius: 16px;
      border: 1px solid var(--line);
      background: #fafbfc;
    }

    .step-card.primary {
      background: linear-gradient(180deg, #f4f9ff 0%, #eef5ff 100%);
      border-color: rgba(45, 140, 255, 0.28);
      box-shadow: 0 8px 22px rgba(14, 114, 237, 0.08);
    }

    .step-label {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px;
      font-size: 0.75rem;
      font-weight: 700;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      color: #6b7280;
    }

    .step-card.primary .step-label {
      color: #0e72ed;
    }

    .step-num {
      display: inline-grid;
      place-items: center;
      width: 22px;
      height: 22px;
      border-radius: 999px;
      background: #e5e7eb;
      color: #374151;
      font-size: 0.72rem;
    }

    .step-card.primary .step-num {
      background: var(--zoom);
      color: #fff;
    }

    .step-card h2 {
      margin: 0 0 6px;
      font-family: "Fraunces", Georgia, serif;
      font-size: 1.18rem;
      font-weight: 700;
      letter-spacing: -0.02em;
      color: var(--ink);
    }

    .step-card p {
      margin: 0 0 14px;
      font-size: 0.95rem;
      line-height: 1.55;
      color: var(--muted);
    }

    .cta-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 13px 22px;
      border-radius: 999px;
      color: #fff;
      font-weight: 700;
      font-size: 0.98rem;
      text-decoration: none;
      transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
    }

    .zoom-btn {
      background: var(--zoom);
      box-shadow: 0 8px 20px rgba(14, 114, 237, 0.28);
    }

    .zoom-btn:hover {
      background: var(--zoom-dark);
      transform: translateY(-2px);
      box-shadow: 0 12px 26px rgba(14, 114, 237, 0.34);
    }

    .wa-btn {
      background: var(--whatsapp);
      box-shadow: 0 8px 20px rgba(37, 211, 102, 0.28);
    }

    .wa-btn:hover {
      background: var(--whatsapp-dark);
      transform: translateY(-2px);
      box-shadow: 0 12px 26px rgba(37, 211, 102, 0.34);
    }

    .cta-btn:active {
      transform: translateY(0);
    }

    .cta-btn svg {
      width: 22px;
      height: 22px;
      flex-shrink: 0;
    }

    @media (max-width: 560px) {
      .cta-btn { width: 100%; }
    }

    .footer {
      text-align: center;
      padding: 0 16px 28px;
      color: #7a7f85;
      font-size: 12px;
    }

    .custom-thank-you {
      text-align: left;
      font-size: 1rem;
      line-height: 1.6;
      color: #3a3f44;
      opacity: 0;
      animation: fade-up 0.65s ease 0.25s forwards;
    }

    .custom-thank-you h1,
    .custom-thank-you h2,
    .custom-thank-you h3 {
      font-family: "Fraunces", Georgia, serif;
      color: var(--ink);
      letter-spacing: -0.02em;
      line-height: 1.25;
      margin: 0 0 0.6em;
    }

    .custom-thank-you p { margin: 0 0 0.9em; }
    .custom-thank-you img { max-width: 100%; height: auto; }
    .custom-thank-you a { color: var(--zoom-dark); }
    .custom-thank-you ul,
    .custom-thank-you ol { margin: 0 0 1em; padding-left: 1.25em; }

    @keyframes rise-in {
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fade-up {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes soft-pulse {
      0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.35); }
      50% { transform: scale(1.04); box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
    }

    @keyframes heart-beat {
      0%, 100% { transform: scale(1); }
      20% { transform: scale(1.18); }
      40% { transform: scale(1); }
    }

    @keyframes shimmer {
      0%, 100% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
    }

    @media (max-width: 560px) {
      .panel-body { padding: 28px 20px 24px; }
      .topbar-inner { padding: 12px 16px; }
      .logo { height: 38px; }
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation: none !important;
        transition: none !important;
      }
      .panel { opacity: 1; transform: none; }
      h1, .lead, .copy, .cta-block, .custom-thank-you { opacity: 1; }
    }
  </style>
</head>
<body>

  <div class="topbar">
    <div class="topbar-inner">
      <img class="logo" src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png" alt="Empower Your Destiny">
      <div class="brand-name">Empower Your Destiny</div>
    </div>
  </div>

  <main class="main">
    <article class="panel" role="status" aria-live="polite">
      <div class="accent" aria-hidden="true"></div>
      <div class="panel-body">
        <?php
          $custom_thank_you_html = '';
          if (!empty($thank_you_template) && is_array($thank_you_template)) {
            $custom_thank_you_html = trim((string)($thank_you_template['body_html'] ?? ''));
          }
        ?>

        <?php if ($custom_thank_you_html !== ''): ?>
          <div class="custom-thank-you">
            <?= $custom_thank_you_html ?>
          </div>
        <?php else: ?>
        <div class="icon-wrap" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
        </div>

        <h1>Thank you for registering <span class="heart" aria-hidden="true">💖</span></h1>
        <p class="lead">We’ve received your registration, and your confirmation email is on its way to you.</p>

        <div class="copy">
          <p>
            Please check your inbox — you should receive an email from
            <strong>EYD Training</strong>
            <span class="email-chip">nlp@empoweryourdestiny.com.au</span>
          </p>
          <p>If you don’t see it right away, be sure to check your spam or promotions folder just in case.</p>
          <p>Inside the email, you’ll find the next steps and/or your login details to set up your account and access your training.</p>
          <p><strong>We’re so excited to have you here — your journey begins now.</strong></p>
        </div>

        <div class="cta-block">
          <div class="next-steps">
            <div class="step-card primary">
              <div class="step-label"><span class="step-num">1</span> Next step</div>
              <h2>Complete your Zoom registration</h2>
              <p>Please register for the Zoom session now so you receive the meeting details and can join on the day.</p>
              <a
                class="cta-btn zoom-btn"
                href="https://us02web.zoom.us/meeting/register/yG1VEFIWQ5OQOhO7g_-GWQ"
                target="_blank"
                rel="noopener noreferrer"
              >
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M4.5 7.2A2.7 2.7 0 0 0 1.8 9.9v4.2A2.7 2.7 0 0 0 4.5 16.8h7.05a2.25 2.25 0 0 0 2.25-2.25V9.45A2.25 2.25 0 0 0 11.55 7.2H4.5Zm14.1.45v8.7c0 .5-.55.8-.97.53l-3.93-2.55V9.67l3.93-2.55c.42-.27.97.03.97.53Z"/>
                </svg>
                Register on Zoom
              </a>
            </div>

            <div class="step-card">
              <div class="step-label"><span class="step-num">2</span> Optional</div>
              <h2>Join our WhatsApp community</h2>
              <p>Stay updated with training announcements and reminders.</p>
              <a
                class="cta-btn wa-btn"
                href="https://chat.whatsapp.com/CyAcIMlQvVJLdDk47wrpfK"
                target="_blank"
                rel="noopener noreferrer"
              >
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                </svg>
                Join WhatsApp Community
              </a>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </article>
  </main>

  <div class="footer">
    © <?= date('Y') ?> Empower Your Destiny.
  </div>

</body>
</html>
