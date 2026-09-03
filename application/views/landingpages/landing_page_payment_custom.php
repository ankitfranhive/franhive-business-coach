<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php
// ---------------------------
// SAFE HELPERS + DEFAULTS
// ---------------------------
$lp = is_array($landing_page ?? null) ? $landing_page : [];

function lp_get($arr, $key, $default = '') {
	return (isset($arr[$key]) && $arr[$key] !== null) ? $arr[$key] : $default;
}
function lp_str($arr, $key, $default = '') {
	$v = lp_get($arr, $key, $default);
	return is_string($v) ? $v : (string)$v;
}
function lp_float($arr, $key, $default = 0.0) {
	$v = lp_get($arr, $key, $default);
	return is_numeric($v) ? (float)$v : (float)$default;
}
function lp_int($arr, $key, $default = 0) {
	$v = lp_get($arr, $key, $default);
	return is_numeric($v) ? (int)$v : (int)$default;
}
function esc($v) {
	return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
if (!function_exists('e')) {
	function e($str) { return html_escape($str, true); } // CI3 helper
}

// ---------------------------
// LANDING PAGE FIELDS (SAFE)
// ---------------------------
$landingPageName = lp_str($lp, 'LANDING_PAGE_NAME', 'Empower Your Destiny');
$bannerImage     = lp_str($lp, 'BANNER_IMAGE', '');
$shareImageRaw   = lp_str($lp, 'SHARE_IMAGE', '');
$leftContentRaw  = lp_str($lp, 'LANDING_PAGE_LEFT_CONTENT', '');
$mainContentRaw  = lp_str($lp, 'LANDING_PAGE_MAIN_CONTENT', '');
$rightContentRaw = lp_str($lp, 'LANDING_PAGE_RIGHT_CONTENT', '');
$thanksContent   = lp_str($lp, 'LANDING_PAGE_THANKS_CONTENT', '');
$currency        = strtolower(trim(lp_str($lp, 'CURRENCY', 'aud'))) ?: 'aud';

$priceT1         = lp_str($lp, 'PRICE_FIRST_TRAINING', '');
$priceT2         = lp_str($lp, 'PRICE_SECOND_TRAINING', '');
$bundleTotal     = lp_str($lp, 'PRICE_BUNDLE_TOTAL', ''); // may be empty
$deposit         = lp_str($lp, 'PRICE_DEPOSIT', '0');

$priceT1Float    = lp_float($lp, 'PRICE_FIRST_TRAINING', 0);
$priceT2Float    = lp_float($lp, 'PRICE_SECOND_TRAINING', 0);
$depositFloat    = lp_float($lp, 'PRICE_DEPOSIT', 0);
$bundleTotalFloat= lp_float($lp, 'PRICE_BUNDLE_TOTAL', 0);

$t1_cents        = (int) round($priceT1Float * 100);
$t2_cents        = (int) round($priceT2Float * 100);
$deposit_cents   = (int) round($depositFloat * 100);
$bundle_cents    = (int) round($bundleTotalFloat * 100);

// initial total shown even if JS fails
$initialTotal = $priceT1Float + $priceT2Float;
$initialTotalCents = (int) round($initialTotal * 100);

$landingPageId = lp_int($lp, 'LANDING_PAGE_ID', 0);

// Coach fields
$coachName     = lp_str($lp, 'COACH_NAME', '');
$coachSubtitle = lp_str($lp, 'COACH_SUBTITLE', '');
$coachPhoto    = lp_str($lp, 'COACH_PHOTO', '');
$coachBioShort = lp_str($lp, 'COACH_BIO_SHORT', '');
$coachBioLong  = lp_str($lp, 'COACH_BIO_LONG', '');

// Testimonials fields
$testImagesRaw = lp_str($lp, 'TESTIMONIALS_IMAGES', '');
$testVideosRaw = lp_str($lp, 'TESTIMONIALS_VIDEOS', '');
$videos = array_filter(array_map('trim', explode(',', $testVideosRaw)));
$images = array_filter(array_map('trim', explode(',', $testImagesRaw)));
$hasTestimonials = (!empty($videos) || !empty($images));

// FAQ
$faqJsonString = lp_str($lp, 'FAQ_JSON', '[]');
$faqs = json_decode($faqJsonString, true);
if (!is_array($faqs)) {
	log_message('error', 'Invalid FAQ_JSON: ' . $faqJsonString);
	$faqs = [];
}

// VIP text
$vipText = lp_str($lp, 'VIP_CARD_TEXT', '');

// Email template
$emailTemplate = lp_str($lp, 'EMAIL_TEMPLATE', '');

// Share meta
$share_title = !empty($landingPageName) ? $landingPageName : 'Empower Your Destiny — Certification';
$raw_left = !empty($leftContentRaw) ? strip_tags($leftContentRaw) : '';
$desc_clean = trim(preg_replace('/\s+/', ' ', $raw_left));
$share_desc = mb_substr($desc_clean, 0, 160) . (mb_strlen($desc_clean) > 160 ? '…' : '');

$share_image = !empty($shareImageRaw)
	? base_url(trim($shareImageRaw))
	: (!empty($bannerImage) ? esc($bannerImage) : base_url('images/eyd-share-default.jpg'));

$share_url = function_exists('current_url') ? current_url()
	: ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://') . ($_SERVER['HTTP_HOST'] ?? '') . ($_SERVER['REQUEST_URI'] ?? '');
?>

	<title><?= esc($landingPageName); ?></title>

	<meta name="description" content="<?= esc($share_desc); ?>">
	<meta property="og:type" content="website">
	<meta property="og:site_name" content="Empower Your Destiny">
	<meta property="og:title" content="<?= esc($share_title); ?>">
	<meta property="og:description" content="<?= esc($share_desc); ?>">
	<meta property="og:image" content="<?= $share_image; ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:url" content="<?= esc($share_url); ?>">

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?= esc($share_title); ?>">
	<meta name="twitter:description" content="<?= esc($share_desc); ?>">
	<meta name="twitter:image" content="<?= $share_image; ?>">

	<link rel="canonical" href="<?= esc($share_url); ?>">
	<link rel="shortcut icon" type="image/png" href="https://www.franhive.com/images/favicon.ico">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url('src/styles/payment_landing_page.css'); ?>">

	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;800&family=Inter:wght@400;600&display=swap" rel="stylesheet">

	<!-- intl-tel-input -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">

	<!-- FIX: ensure phone input aligns same width/height as other form controls -->
	<style>
		.iti { width: 100%; }
		.iti input.form-control { width: 100%; }
	</style>
</head>

<body class="bg-skin bg-logo">
	<div class="container">

		<div class="my-header">
			<div class="mh-wrap">
				<div class="mh-left">
					<a class="mh-brand" href="<?= base_url(); ?>" aria-label="Empower Your Destiny — Home">
						<img class="mh-logo" src="https://empoweryourdestiny.com.au/wp-content/uploads/2023/09/EYD-Logo-without-tag-line.png" alt="Empower Your Destiny (EYD) logo">
					</a>
					<div class="mh-text">
						<?php if ($landingPageId != 40 || $landingPageId != 43): ?>
						<!-- <div class="mh-kicker">EYD Certification</div> -->
						<?php endif; ?>
						<h1 class="mh-title"><?= esc($landingPageName); ?></h1>
						<div class="mh-meta"></div>
					</div>
				</div>
			</div>
		</div>

		<section class="hero-split my-3 with-halo">
			<div class="row align-items-center">
				<div class="col-md-6">
					<?php if (!empty($mainContentRaw)): ?>
						<?= $mainContentRaw; ?>
					<?php endif; ?>
					<div class="hero-cta">
						<a href="#register-block" class="btn btn-hero">Register now</a>
						<a href="#faqAccordion" class="btn btn-outline-hero">View FAQs</a>
					</div>
				</div>

				<div class="col-md-6">
					<?php if (!empty($bannerImage)): ?>
						<div class="hero-image-wrap mt-3 mt-md-0">
							<img src="<?= esc($bannerImage) ?>" alt="Program banner" class="hero-img" />
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>

		<div class="content">
			<div class="main-content">
				<?php if (!empty($leftContentRaw)): ?>
					<?= $leftContentRaw; ?>
				<?php endif; ?>

				<?php
					$showCoach = (!empty($coachName) || !empty($coachSubtitle) || !empty($coachPhoto) || !empty($coachBioShort) || !empty($coachBioLong));
				?>
				<?php if ($showCoach): ?>
					<div class="duo-section" id="coach">
						<div class="coach-title">Meet Your Trainer!</div>
						<div class="coach-card">
							<div class="row coach-intro align-items-start">
								<?php if (!empty($coachPhoto)): ?>
									<div class="col-md-4 mb-3 mb-md-0">
										<div class="photo-wrap">
											<img src="<?= esc($coachPhoto); ?>" alt="<?= esc($coachName ?: 'Coach'); ?>" class="coach-img">
										</div>
									</div>
								<?php endif; ?>

								<div class="<?= !empty($coachPhoto) ? 'col-md-8' : 'col-12'; ?>">
									<?php if (!empty($coachName)): ?>
										<div class="coach-name"><?= esc($coachName); ?></div>
									<?php endif; ?>
									<?php if (!empty($coachSubtitle)): ?>
										<div class="coach-sub"><?= esc($coachSubtitle); ?></div>
									<?php endif; ?>
									<?php if (!empty($coachBioShort)): ?>
										<p class="coach-text mb-2"><?= $coachBioShort; ?></p>
									<?php endif; ?>
								</div>
							</div>

							<?php if (!empty($coachBioLong)): ?>
								<div class="coach-details">
									<div class="row">
										<div class="col-12">
											<?= $coachBioLong; ?>
											<div class="coach-cta">
												<a class="btn-msg" href="https://m.me/barinderjeet.kaur.39" target="_blank" rel="noopener" aria-label="Message on Facebook Messenger">
													<span>Message on <span class="dot">Messenger</span></span>
												</a>
												<a class="btn-wa" href="https://wa.me/61426886501?text=Hi%20Barinderjeet%2C%20I%27m%20interested%20in%20the%20Destiny%20Duo%20Certification." target="_blank" rel="noopener" aria-label="Chat on WhatsApp Business">
													<span>Chat on <span class="dot">WhatsApp</span></span>
												</a>
											</div>
										</div>
									</div>
								</div>
							<?php endif; ?>

						</div>
					</div>
				<?php endif; ?>

				<div class="duo-section">
					<div class="duo-h2">FAQ — your questions, answered</div>
					<div id="faqAccordion" class="accordion">
						<?php if (empty($faqs)): ?>
							<div class="card">
								<div class="card-header">
									<div class="card-body">No FAQs available right now.</div>
								</div>
							</div>
						<?php else: ?>
							<?php foreach ($faqs as $i => $item):
								$q = isset($item['q']) ? $item['q'] : '';
								$a = isset($item['a']) ? $item['a'] : '';
								$n = $i + 1;
								$hid = "faq-h{$n}";
								$cid = "faq-c{$n}";
							?>
								<div class="card">
									<div class="card-header" id="<?= e($hid) ?>">
										<button class="btn btn-link btn-faq collapsed" type="button" data-toggle="collapse" data-target="#<?= e($cid) ?>" aria-expanded="false" aria-controls="<?= e($cid) ?>">
											<?= e($q) ?> <span class="chev collapsed">⌄</span>
										</button>
									</div>
									<div id="<?= e($cid) ?>" class="collapse" aria-labelledby="<?= e($hid) ?>" data-parent="#faqAccordion">
										<div class="card-body"><?= nl2br(e($a)) ?></div>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>

			</div>

			<div class="side-content">

				<?php if ($hasTestimonials): ?>
					<h2 class="text-center text-brandcolor">Testimonials</h2>
					<div class="testimonials-list">
						<?php
						// Videos (ONCE)
						foreach ($videos as $url):
							$embed = '';
							if (preg_match('#/shorts/([^/?]+)#i', $url, $m)) {
								$embed = 'https://www.youtube.com/embed/' . $m[1];
							} elseif (preg_match('#v=([^&]+)#i', $url, $m)) {
								$embed = 'https://www.youtube.com/embed/' . $m[1];
							} else {
								// If DB stores only youtube id like "kRl9aP8l0rU"
								if (preg_match('#^[a-zA-Z0-9_-]{6,}$#', $url)) {
									$embed = 'https://www.youtube.com/embed/' . $url;
								}
							}
							if ($embed):
						?>
							<div class="testimonial-video">
								<iframe src="<?= esc($embed) ?>" title="Testimonial video" loading="lazy"
									allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
									allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
							</div>
						<?php endif; endforeach; ?>

						<?php foreach ($images as $image): ?>
							<div class="testimonial-image">
								<img src="<?= base_url() . esc($image) ?>" class="img-fluid w-100" alt="Testimonial Image">
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if (!empty($rightContentRaw)): ?>
					<?= $rightContentRaw; ?>
				<?php endif; ?>

			</div>
		</div>

		<div class="contact-form" id="register-block">
			<div class="regcard">
				<div class="reg-head">
					<div class="reg-icon"></div>
					<div>
						<h5 class="mb-0">Let’s Get You Signed Up!</h5>
						<div class="muted">Kindly leave your details below to easily connect with our team and community!</div>
					</div>
				</div>

				<div id="contact-status"></div>

				<div class="reg-body">
					<form id="contactForm">
						<input type="hidden" name="EMAIL_TEMPLATE" value="<?= esc($emailTemplate); ?>">
							<input type="hidden" name="LANDING_PAGE_NAME" value="<?= esc($landingPageName); ?>">

						<div class="form-row-gap">
							<div class="form-group mb-2">
								<label for="name">Full name</label>
								<input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Jane Doe">
								<div class="invalid-feedback">Please enter your full name.</div>
							</div>

							<div class="form-group mb-2">
								<label for="mobile">Mobile</label>
								<input type="tel" class="form-control" id="mobile" name="mobile" required placeholder="e.g. 4xx xxx xxx">
								<input type="hidden" name="mobile_full" id="mobile_full" value="">
								<input type="hidden" name="country_code" id="country_code" value="">
								<div class="invalid-feedback">Please enter a valid mobile number.</div>
							</div>
						</div>

						<div class="form-group mb-2">
							<label for="email">Email</label>
							<input type="email" class="form-control" id="email" name="email" required placeholder="you@domain.com">
							<div class="hint">We’ll send your confirmation and joining details here.</div>
							<div class="invalid-feedback">Please enter a valid email address.</div>
						</div>

						<div class="form-group mb-2">
							<label for="platform">Preferred platform to stay in touch</label>
							<select class="form-control" id="platform" name="platform" required>
								<option value="" disabled selected>Select an option</option>
								<option value="1">WhatsApp</option>
								<option value="2">Messenger</option>
								<option value="3">Email</option>
							</select>
							<div class="invalid-feedback">Please select a preferred platform.</div>
						</div>

						<?php if ($landingPageId == 34): ?>
							<div class="form-group mb-2">
								<label for="gift_id">Select which gift you want to receive</label>
								<select class="form-control" id="gift_id" name="gift_id" required>
									<option value="" disabled selected>Select an option</option>
									<?php for ($i = 1; $i <= 12; $i++): ?>
										<option value="<?= $i ?>">Gift <?= $i ?></option>
									<?php endfor; ?>
								</select>
								<div class="invalid-feedback">Please select a gift.</div>
							</div>
						<?php endif; ?>

						<div class="form-group mb-2">
							<div class="form-check">
								<input class="form-check-input" type="checkbox" id="consent" name="consent" value="1" required>
								<label class="form-check-label" for="consent">
									I agree to the Terms &amp; Conditions and consent to receive communications about this event and related products and/or services from the organizer.
									I understand I may withdraw my consent at any time.
								</label>
								<div class="invalid-feedback">You must agree before submitting.</div>
							</div>
						</div>

						<div class="actions">
							<?php if ($landingPageId == 20): ?>
								<button type="submit" class="btn-reg">YES! I Want to Activate My Relationships <span class="chev">›</span></button>
							<?php else: ?>
								<button type="submit" class="btn-reg">Submit <span class="chev">›</span></button>
							<?php endif; ?>
						</div>


					</form>
				</div>

			</div>
		</div>

		<hr class="my-4">

		<?php $showPaymentBlock = (!empty($priceT1) || !empty($priceT2)); ?>
		<?php if ($showPaymentBlock): ?>
			<div id="payment-block" class="mt-4 theme-green with-halo">
				<div class="card pcard">
					<div class="pcard-head">
						<div class="icon"></div>
						<div>
							<div class="h5 mb-0">Save Your Spot</div>
							<div class="muted">Secure your spot today using Stripe or direct bank transfer</div>
						</div>
					</div>

					<div class="pcard-body">

					<?php if (!empty($priceT1)): ?>
    <div class="rowline">
        <div>
            <?php if ($landingPageId == 43): ?>
                <strong>Universal Life Force Activation | Reiki Level 1</strong>
            <?php else: ?>
				<div>Individual: <strong>Training-1</strong></div>
            <?php endif; ?>
        </div>

        <div>
            <strong 
                id="item-nlp"
                data-amount-cents="<?= (int) $t1_cents ?>"
                data-currency="<?= esc($currency) ?>"
            >
                AUD$<?= esc($priceT1) ?>
            </strong>
        </div>
    </div>
<?php endif; ?>

						<?php if (!empty($priceT2)): ?>
							<div class="rowline">
								<div>Individual: <strong>Training-2</strong></div>
								<div>
									<strong id="item-reiki" data-amount-cents="<?= (int)$t2_cents ?>" data-currency="<?= esc($currency) ?>">
										AUD$<?= esc($priceT2) ?>
									</strong>
								</div>
							</div>
						<?php endif; ?>

						<div class="rowline discount-row d-none" id="discount-row">
							<div>Discount</div>
							<div><strong id="discount-amount">−AUD$0.00</strong></div>
						</div>

						<div class="rowline" style="border-top:1px solid #eef2f7">
							<div class="text-uppercase muted" style="letter-spacing:.04em">Total</div>
							<div><strong id="total-price">AUD$<?= number_format($initialTotal, 2); ?></strong></div>
						</div>

						<div class="coupon-wrap">
							<label class="sr-only" for="coupon">Coupon</label>
							<input type="text" class="form-control" id="coupon" placeholder="Coupon (e.g. DUOCOPONCODE)">
							<button id="apply-coupon" class="btn btn-apply" type="button">Apply Coupon</button>
						</div>

						<?php if (!empty($vipText)): ?>
							<div id="coupon-nudge" class="promo-nudge emphasis" role="note" aria-live="polite">
								<div class="burst" aria-hidden="true"></div>
								<div>
									<div class="headline">Bundle both courses & save up to 50%</div>
									<div class="sub"><?= $vipText; ?> <a href="#coupon" id="apply-bundle" class="cta-link">Apply now</a></div>
								</div>
							</div>
						<?php else: ?>
							<div id="coupon-nudge" class="promo-nudge emphasis d-none" role="note" aria-live="polite"></div>
						<?php endif; ?>

						<div id="coupon-feedback" class="coupon-help"></div>

						<div class="secure"><span>SSL encrypted • No hidden fees • Instant email receipt</span></div>

						<div class="btn-row">
							<div class="pay-grid">

								<button id="pay-bank" class="btn btn-bank" type="button" aria-live="polite">
									<div class="btn-title" id="bank-pay-label">Direct Bank Transfer</div>
									<div class="cta-price">
										<span class="pill-amount" id="bank-pay-deposit">AUD$<?= number_format($depositFloat, 2); ?></span>
										<span class="sep">•</span>
										<span class="muted small">
											Remaining <strong id="bank-pay-remaining">AUD$<?= number_format(max(0, $initialTotal - $depositFloat), 2); ?></strong> later
											<span id="bank-pay-remaining-text"> (** Apply your coupon to get 50% off)</span>
										</span>
									</div>
								</button>

								<button id="pay-now" class="btn btn-accent" type="button" aria-live="polite">
									<div class="btn-title" id="pay-now-label">Secure Online Checkout</div>
									<div class="cta-price">
										<span class="pill-amount" id="pay-now-deposit">AUD$<?= number_format($depositFloat, 2); ?></span>
										<span class="sep">•</span>
										<span class="muted small">
											Remaining <strong id="pay-now-remaining">AUD$<?= number_format(max(0, $initialTotal - $depositFloat), 2); ?></strong> later
										</span>
									</div>
									<span id="bank-pay-remaining-text"> (** Apply your coupon to get 50% off)</span>
								</button>

							</div>
						</div>

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
												<div class="font-weight-bold" id="bank-modal-now">AUD$<?= number_format($depositFloat, 2); ?></div>
											</div>
											<div class="text-muted">•</div>
											<div>
												<div class="small text-uppercase text-muted">Balance later</div>
												<div class="font-weight-bold" id="bank-modal-later">AUD$<?= number_format(max(0, $initialTotal - $depositFloat), 2); ?></div>
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

						<input type="hidden" id="promotion-code-id" value="">
						<input type="hidden" id="applied-coupon" value="">

					</div>
				</div>
			</div>
		<?php endif; ?>

		<!-- Help / Connect -->
		<section id="help-connect" class="mt-4">
			<div class="connect-card">
				<div class="connect-title">Still have questions?</div>
				<div class="connect-sub">Ask us directly — we reply fast. Choose an option:</div>
				<div class="connect-cta">
					<a class="chip btn-wa" id="wa-barinder" href="https://wa.me/61426886501?text=Hi%20Barinderjeet%2C%20I%27m%20interested%20in%20the%20Destiny%20Duo%20Certification." target="_blank" rel="noopener" aria-label="Chat with Barinder on WhatsApp">
						<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<path d="M12 2.6a8.9 8.9 0 00-7.8 13.2l-1 3.7 3.8-1A8.9 8.9 0 1012 2.6Z" stroke="#25D366" stroke-width="1.5" />
							<path d="M8.9 10.2c.1-.2.3-.3.5-.2l1.1.5c.2.1.3.3.2.5-.2.5 0 1.1.4 1.5l.4.4c.4.4 1 .6 1.5.4.2-.1.4 0 .5.2l.5 1.1c.1.2 0 .4-.2.5-1.1.6-2.5.4-3.5-.6l-.9-.9c-1-1-1.2-2.4-.5-3.4Z" fill="#25D366" />
						</svg>
						<span>WhatsApp <span class="dot-wa">Messenger</span></span>
					</a>

					<a class="chip btn-msg" href="https://m.me/empoweryourdestiny" target="_blank" rel="noopener" aria-label="Message EYD Team on Facebook Messenger">
						<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<path d="M12 2.5C6.7 2.5 2.5 6.4 2.5 11.3c0 2.8 1.4 5.2 3.5 6.8v3.4l3.3-1.8c.9.3 1.8.4 2.7.4 5.3 0 9.5-3.9 9.5-8.8S17.2 2.5 12 2.5Z" stroke="#0084FF" stroke-width="1.5" />
							<path d="M7 13.2l3.2-3 2.3 2 3.5-3" stroke="#0084FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<span>Facebook <span class="dot-msg"> Page Messenger</span></span>
					</a>

					<a class="chip btn-msg" href="https://m.me/barinderjeet.kaur.39" target="_blank" rel="noopener" aria-label="Message Barinder on Facebook Messenger">
						<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
							<path d="M12 2.5C6.7 2.5 2.5 6.4 2.5 11.3c0 2.8 1.4 5.2 3.5 6.8v3.4l3.3-1.8c.9.3 1.8.4 2.7.4 5.3 0 9.5-3.9 9.5-8.8S17.2 2.5 12 2.5Z" stroke="#0084FF" stroke-width="1.5" />
							<path d="M7 13.2l3.2-3 2.3 2 3.5-3" stroke="#0084FF" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
						</svg>
						<span>Private Messenger <span class="dot-msg"> Chat with Your Trainer</span></span>
					</a>
				</div>
			</div>
			<div id="connect-copy-toast" role="status" aria-live="polite">Intro copied — paste in Messenger ✅</div>
		</section>

		<script src="https://js.stripe.com/v3/"></script>
	</div>

	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>

	<!-- intl-tel-input JS -->
	<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"></script>

	<!-- PAYMENT SCRIPT (unchanged functionality) -->
	<script>
		const DEPOSIT_CENTS = <?= (int)$deposit_cents ?>;
		const BUNDLE_TOTAL_CENTS = <?= (int)$bundle_cents ?>;
		const locale = 'en-AU';
		const currency = 'aud';

		function moneyAU(cents) {
			return 'AUD$' + new Intl.NumberFormat(locale, {
				minimumFractionDigits: 2,
				maximumFractionDigits: 2
			}).format((cents || 0) / 100);
		}

		function updatePayCta(totalCents) {
			const $btn = $("#pay-now");
			const $label = $("#pay-now-label");
			const $dep = $("#pay-now-deposit");
			const $rem = $("#pay-now-remaining");

			if(!$btn.length) return;

			if (BUNDLE_TOTAL_CENTS > 0 && totalCents === BUNDLE_TOTAL_CENTS) {
				const remaining = Math.max(0, totalCents - DEPOSIT_CENTS);
				$btn.addClass("is-deposit").data("mode", "deposit");
				$label.text("Secure Online Checkout " + moneyAU(DEPOSIT_CENTS) + " deposit");
				$dep.text(moneyAU(DEPOSIT_CENTS));
				$rem.text(moneyAU(remaining));
			} else {
				$btn.removeClass("is-deposit").data("mode", "full");
				$label.text("Secure Online Checkout " + moneyAU(totalCents));
				const remaining = Math.max(0, totalCents - DEPOSIT_CENTS);
				$dep.text(moneyAU(DEPOSIT_CENTS));
				$rem.text(moneyAU(remaining));
			}

			const bankRemaining = Math.max(0, totalCents - DEPOSIT_CENTS);
			$("#bank-pay-deposit").text(moneyAU(DEPOSIT_CENTS));
			$("#bank-pay-remaining").text(moneyAU(bankRemaining));
			$("#bank-modal-now").text(moneyAU(DEPOSIT_CENTS));
			$("#bank-modal-later").text(moneyAU(bankRemaining));

			if (BUNDLE_TOTAL_CENTS > 0 && totalCents === BUNDLE_TOTAL_CENTS) {
				$("#bank-pay-label").text("Direct Bank Transfer — " + moneyAU(DEPOSIT_CENTS) + " deposit");
			} else {
				$("#bank-pay-label").text("Direct Bank Transfer — Pay " + moneyAU(totalCents));
			}
		}

		$(function() {
			const $nlp = $("#item-nlp");
			const $reiki = $("#item-reiki");
			const $total = $("#total-price");
			const $coupon = $("#coupon");
			const $couponFeedback = $("#coupon-feedback");
			const $promoId = $("#promotion-code-id");
			const $applyBtn = $("#apply-coupon");
			const $payBtn = $("#pay-now");

			function centsFrom($el) {
				if(!$el || !$el.length) return 0;
				return parseInt($el.data("amount-cents"), 10) || 0;
			}
			function baseTotalCents() {
				return centsFrom($nlp) + centsFrom($reiki);
			}
			function setTotal(c) {
				const formatted = moneyAU(c);
				$total.text(formatted);
				updatePayCta(c);
				window.currentTotalCents = c;
			}
			function showDiscount(offCents) {
				$("#discount-row").removeClass("d-none");
				$("#discount-amount").text("−" + moneyAU(offCents));
			}
			function hideDiscount() {
				$("#discount-row").addClass("d-none");
				$("#discount-amount").text("−" + moneyAU(0));
			}

			const initial = baseTotalCents();
			window.currentTotalCents = initial;
			setTotal(initial);

			$applyBtn.on("click", function() {
				const code = ($coupon.val() || "").trim();
				const amountCents = baseTotalCents();

				if(!code) {
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
				}).done(function(resp) {
					if(resp && resp.status === "success") {
						setTotal(resp.data.total_cents);
						$promoId.val(resp.data.promotion_code_id || "");
						$couponFeedback.html('<span class="text-success">Coupon applied: ' + resp.data.code + ' (−' + moneyAU(resp.data.discount_cents) + ')</span>');
						showDiscount(resp.data.discount_cents);
						$("#coupon-nudge").addClass("d-none");
					} else {
						setTotal(baseTotalCents());
						$promoId.val("");
						hideDiscount();
						$coupon.addClass("is-invalid");
						$couponFeedback.html('<span class="text-danger">' + ((resp && resp.message) ? resp.message : "Invalid or expired coupon") + '</span>');
						$("#coupon-nudge").removeClass("d-none");
					}
				}).fail(function(xhr) {
					setTotal(amountCents);
					$promoId.val("");
					hideDiscount();
					let msg = 'Could not validate coupon. Please try again.';
					try { const j = JSON.parse(xhr.responseText || ''); if(j.message) msg = j.message; } catch (e) {}
					$coupon.addClass("is-invalid");
					$couponFeedback.html('<span class="text-danger">' + msg + '</span>');
					$("#coupon-nudge").removeClass("d-none");
				}).always(function() {
					$applyBtn.prop("disabled", false).text(orig);
				});
			});

			$("#pay-now").off("click.eyd").on("click.eyd", function() {
				if(!$payBtn.length) return;

				const mode = ($payBtn.data("mode") || "full").toString();
				const isDepositMode = (mode === "deposit");
				const promotionCodeId = $("#promotion-code-id").val() || "";

				const totalCents = (function() {
					const nlp = centsFrom($nlp);
					const reiki = centsFrom($reiki);
					let discount = 0;
					if(!$("#discount-row").hasClass("d-none")) {
						const txt = ($("#discount-amount").text() || "").replace(/[^\d.]/g, '');
						if(txt) discount = Math.round(parseFloat(txt) * 100);
					}
					return Math.max(0, nlp + reiki - discount);
				})();

				const planMode = $('input[name="plan_mode"]').length ? ($('input[name="plan_mode"]:checked').val() || 'auto') : 'auto';
				const balanceDate = $("#balance-date").length ? ($("#balance-date").val() || "") : "";

				const endpoint = isDepositMode
					? "<?= base_url('/PaymentController/create_checkout_session_deposit') ?>"
					: "<?= base_url('/PaymentController/create_checkout_session') ?>";

				const payload = isDepositMode ? {
					deposit_cents: DEPOSIT_CENTS,
					total_cents: totalCents,
					currency: currency,
					landing_page_id: "<?= (int)$landingPageId; ?>",
					product_name: "<?= esc($landingPageName); ?>",
					promotion_code_id: promotionCodeId,
					plan_mode: planMode,
					balance_due_date: balanceDate
				} : {
					amount_cents: totalCents,
					currency: currency,
					landing_page_id: "<?= (int)$landingPageId; ?>",
					product_name: "<?= esc($landingPageName); ?>",
					promotion_code_id: promotionCodeId
				};

				const $title = $("#pay-now-label").length ? $("#pay-now-label") : $payBtn;
				const orig = $title.text();

				$payBtn.prop("disabled", true);
				$title.text("Redirecting…");

				$.ajax({
					url: endpoint,
					type: "POST",
					dataType: "json",
					data: payload
				}).done(function(resp) {
					if(resp && resp.status === "success") {
						const stripe = Stripe("<?= STRIPE_PUBLISHABLE_KEY ?>");
						stripe.redirectToCheckout({ sessionId: resp.data.session_id });
					} else {
						alert((resp && resp.message) ? resp.message : "Unable to start checkout.");
						$payBtn.prop("disabled", false);
						$title.text(orig);
					}
				}).fail(function(xhr, status) {
					let body = xhr.responseText || "";
					let msg = "Checkout failed.";
					try { const j = JSON.parse(body); if(j.message) msg = j.message; } catch (e) {}
					alert("HTTP " + xhr.status + " — " + status + "\n" + msg + (body ? ("\n\n" + body) : ""));
					$payBtn.prop("disabled", false);
					$title.text(orig);
				});
			});

			$("#pay-bank").off("click").on("click", function() {
				const prefill = ($("#email").val() || "").trim();
				$("#bankEmail").val(prefill).removeClass("is-invalid");
				$("#bankModalStatus").removeClass("text-success text-danger").empty();

				const total = window.currentTotalCents || baseTotalCents();
				const remaining = Math.max(0, total - DEPOSIT_CENTS);

				$("#bank-modal-now").text(moneyAU(DEPOSIT_CENTS));
				$("#bank-modal-later").text(moneyAU(remaining));

				$("#bankModal").modal("show");
			});

			$("#bank-send-confirm").off("click").on("click", function() {
				const email = ($("#bankEmail").val() || "").trim();
				if(!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
					$("#bankEmail").addClass("is-invalid");
					return;
				}
				$("#bankEmail").removeClass("is-invalid");

				const $btn = $(this);
				const orig = $btn.text();
				$btn.prop("disabled", true).text("Sending…");

				const total = window.currentTotalCents || baseTotalCents();
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
						landing_page_id: "<?= (int)$landingPageId; ?>",
						product_name: "<?= esc($landingPageName); ?>"
					}
				}).done(function(resp) {
					if(resp && resp.status === "success") {
						$("#bankModalStatus").removeClass("text-danger").addClass("text-success").text("We’ve emailed you the bank transfer details. Please check your inbox (and spam).");
						setTimeout(function() { $("#bankModal").modal("hide"); }, 1200);
					} else {
						const msg = (resp && resp.message) ? resp.message : "Could not send email. Please try again.";
						$("#bankModalStatus").removeClass("text-success").addClass("text-danger").text(msg);
					}
				}).fail(function(xhr) {
					let msg = "Could not send email. Please try again.";
					try { const j = JSON.parse(xhr.responseText || ""); if(j.message) msg = j.message; } catch (e) {}
					$("#bankModalStatus").removeClass("text-success").addClass("text-danger").text(msg);
				}).always(function() {
					$btn.prop("disabled", false).text(orig);
				});
			});
		});
	</script>

	<!-- CONTACT FORM SCRIPT (FIXED: single block, no global iti reference errors) -->
	<script>
		$(function () {
			const $form = $("#contactForm");
			const $status = $("#contact-status");
			const $button = $("#contactForm button[type='submit']");
			const btnTxt = $button.text();

			const $name = $("#name");
			const $mobile = $("#mobile");
			const $email = $("#email");
			const $platform = $("#platform");
			const $gift = $("#gift_id"); // optional
			const $consent = $("#consent");

			const $mobileFull = $("#mobile_full");
			const $countryCode = $("#country_code");

			// init intl-tel-input
			const iti = window.intlTelInput($mobile.get(0), {
				initialCountry: "in",
				separateDialCode: true,
				nationalMode: true,
				autoPlaceholder: "polite",
				utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js"
			});

			function syncCountryFields() {
				const data = iti.getSelectedCountryData() || {};
				$countryCode.val(data.dialCode ? ("+" + data.dialCode) : "");
			}
			syncCountryFields();

			function setInvalid($el, msg) {
				$el.addClass("is-invalid").removeClass("is-valid");
				const $container = $el.closest(".form-check").length ? $el.closest(".form-check") : $el.closest(".form-group");
				const $fb = $container.find(".invalid-feedback").first();
				if ($fb.length && msg) $fb.text(msg);
				if ($fb.length) $fb.show();
			}
			function setValid($el) {
				$el.removeClass("is-invalid").addClass("is-valid");
				const $container = $el.closest(".form-check").length ? $el.closest(".form-check") : $el.closest(".form-group");
				const $fb = $container.find(".invalid-feedback").first();
				if ($fb.length) $fb.hide();
			}
			function clearState($el) {
				$el.removeClass("is-invalid is-valid");
				const $container = $el.closest(".form-check").length ? $el.closest(".form-check") : $el.closest(".form-group");
				const $fb = $container.find(".invalid-feedback").first();
				if ($fb.length) $fb.hide();
			}

			function validateName() {
				const v = ($name.val() || "").trim();
				if (v.length < 2) { setInvalid($name, "Please enter your full name."); return false; }
				setValid($name); return true;
			}
			function validateEmail() {
				const v = ($email.val() || "").trim();
				const ok = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v);
				if (!ok) { setInvalid($email, "Please enter a valid email address."); return false; }
				setValid($email); return true;
			}
			function validatePlatform() {
				if (!$platform.val()) { setInvalid($platform, "Please select a preferred platform."); return false; }
				setValid($platform); return true;
			}
			function validateGiftIfPresent() {
				if (!$gift.length) return true;
				if (!$gift.val()) { setInvalid($gift, "Please select a gift."); return false; }
				setValid($gift); return true;
			}
			function validateConsent() {
				if ($consent.length && !$consent.is(":checked")) { setInvalid($consent, "You must agree before submitting."); return false; }
				if ($consent.length) setValid($consent);
				return true;
			}
			function validateMobile() {
				const raw = ($mobile.val() || "").trim();
				if (!raw) { setInvalid($mobile, "Mobile number is required."); return false; }
				if (!iti.isValidNumber()) { setInvalid($mobile, "Please enter a valid mobile number for the selected country."); return false; }
				setValid($mobile);
				$mobileFull.val(iti.getNumber()); // E.164
				syncCountryFields();
				return true;
			}

			function validateAll() {
				const ok = (validateName() & validateMobile() & validateEmail() & validatePlatform() & validateGiftIfPresent() & validateConsent());
				return !!ok;
			}

			// Live validation
			$name.on("input", () => clearState($name)).on("blur", validateName);
			$email.on("input", () => clearState($email)).on("blur", validateEmail);
			$platform.on("change", function () { clearState($platform); validatePlatform(); });

			if ($gift.length) $gift.on("change", function () { clearState($gift); validateGiftIfPresent(); });
			if ($consent.length) $consent.on("change", function () { clearState($consent); validateConsent(); });

			$mobile.on("input", () => clearState($mobile));
			$mobile.on("blur", validateMobile);
			$mobile.on("countrychange", function () {
				syncCountryFields();
				clearState($mobile);
				if (($mobile.val() || "").trim()) validateMobile();
			});

			function refreshCsrf(resp) {
				if(resp && resp.csrf_token_name && resp.csrf_hash) {
					const name = resp.csrf_token_name;
					const hash = resp.csrf_hash;
					let $hidden = $form.find("input[name='" + name + "']");
					if(!$hidden.length) {
						$hidden = $("<input>", { type: "hidden", name: name }).appendTo($form);
					}
					$hidden.val(hash);
				}
			}

			$form.on("submit", function (e) {
				e.preventDefault();
				$status.removeClass("alert alert-success alert-danger").empty();

				if (!validateAll()) {
					const $firstInvalid = $form.find(".is-invalid").first();
					if ($firstInvalid.length) $firstInvalid.focus();
					return;
				}

				// Always set latest values before sending
				$mobileFull.val(iti.getNumber());
				syncCountryFields();

				$button.prop("disabled", true).text("Submitting…");

				$.ajax({
					url: "<?= base_url('LandingPageController/submit_contact') ?>",
					type: "POST",
					dataType: "json",
					data: $form.serialize()
				}).done(function(resp) {
					refreshCsrf(resp);
					if(resp && resp.status === "success") {
						$status.addClass("alert alert-success").html(<?= json_encode($thanksContent); ?>);
						$("#register-block .reg-head").slideUp(200);
						$("#register-block .reg-body").slideUp(200);
						// $form.slideUp(200);
					} else {
						const msg = (resp && resp.message) ? resp.message : "Something went wrong. Please try again.";
						$status.addClass("alert alert-danger").text(msg);
						$button.prop("disabled", false).text(btnTxt);
					}
				}).fail(function(xhr) {
					let msg = "An error occurred. Please try again.";
					try { const j = JSON.parse(xhr.responseText || ""); if(j && j.message) msg = j.message; } catch (e) {}
					$status.addClass("alert alert-danger").text(msg);
					$button.prop("disabled", false).text(btnTxt);
				});
			});
		});
	</script>
</body>
</html>
