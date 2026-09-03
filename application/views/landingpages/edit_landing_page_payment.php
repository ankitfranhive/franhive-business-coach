<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('includes/header'); ?>



<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            <h4>Edit Landing Page</h4>
          </div>
          <p class="mb-0 text-muted small">
            Type 1 = Basic (existing template), Type 2 = Advanced (new Destiny Duo template).
          </p>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('/landing-pages'); ?>" role="button">Back To Pages List</a>
          <a class="btn btn-warning" href="<?php echo base_url('video-page-edit/' . $landing_page['LANDING_PAGE_ID']); ?>" role="button">Videos</a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <form action="<?= base_url('LandingPageController/updateLandingPage') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $landing_page['LANDING_PAGE_ID'] ?>">
        <input type="hidden" name="LANDING_PAGE_TYPE" id="LANDING_PAGE_TYPE" value="<?= (int)($landing_page['LANDING_PAGE_TYPE'] ?? 1) ?>">

        <!-- Type selector (UI only; hidden field posts the value) -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="typeSelector">Landing Page Type <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <?php $lpType = (string)($landing_page['LANDING_PAGE_TYPE'] ?? '1'); ?>
                <select id="typeSelector" class="form-control" aria-describedby="typeHelp">
                  <option value="1" <?= $lpType === '1' ? 'selected' : '' ?>>1 — Basic</option>
                  <option value="2" <?= $lpType === '2' ? 'selected' : '' ?>>2 — Advanced</option>
                </select>
                <small id="typeHelp" class="text-muted">Switching type will show/hide extra fields accordingly.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- ===== BASIC / COMMON FIELDS (shared) ===== -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Basic</h5></div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_NAME">Landing Page Title <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <input class="form-control" id="LANDING_PAGE_NAME" type="text" name="LANDING_PAGE_NAME" placeholder="Title" value="<?= htmlspecialchars($landing_page['LANDING_PAGE_NAME'] ?? '') ?>" required />
              </div>
            </div>
          </div>
        </div>

        <!-- Media -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Media</h5></div>
        <div class="row">
          <!-- Banner -->
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="BANNER_IMAGE">Banner Image <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input class="form-control" id="BANNER_IMAGE" name="BANNER_IMAGE" type="file" accept="image/*" />
                <?php if (!empty($landing_page['BANNER_IMAGE'])): ?>
                  <small class="d-block mt-1"><a href="<?= $landing_page['BANNER_IMAGE'] ?>" target="_blank">View Current Image</a></small>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Share image (Type 2) -->
          <div class="col-md-6" data-section="type2">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="SHARE_IMAGE">Share Image (OG/Twitter)</label>
              <div class="col-md-8">
                <input class="form-control" id="SHARE_IMAGE" name="SHARE_IMAGE" type="file" accept="image/*" />
                <?php if (!empty($landing_page['SHARE_IMAGE'])): ?>
                  <small class="d-block mt-1"><a href="<?= $landing_page['SHARE_IMAGE'] ?>" target="_blank">View Current Share Image</a></small>
                <?php endif; ?>
                <small class="text-muted">If empty, front-end may fall back to the Banner Image.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Email + Templates -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Emails / Templates</h5></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="CC_EMAIL">CC Email <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input class="form-control" id="CC_EMAIL" type="email" name="CC_EMAIL" placeholder="Email" value="<?= htmlspecialchars($landing_page['CC_EMAIL'] ?? '') ?>" />
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="EMAIL_TEMPLATE">Email Template (General) <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="EMAIL_TEMPLATE" id="EMAIL_TEMPLATE" class="form-control">
                  <option value="-1" <?= ((int)($landing_page['EMAIL_TEMPLATE'] ?? -1) === -1) ? 'selected' : '' ?>>Select</option>
                  <?php foreach ($all_templates as $template) : ?>
                    <option value="<?php echo $template['TEMPLATE_ID']; ?>" <?= ((int)($landing_page['EMAIL_TEMPLATE'] ?? 0) === (int)$template['TEMPLATE_ID']) ? 'selected' : '' ?>>
                      <?php echo htmlspecialchars($template['TEMPLATE_NAME']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-6 mt-2">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="REGISTERED_TEMPLATE">Template on Registration <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="REGISTERED_TEMPLATE" id="REGISTERED_TEMPLATE" class="form-control">
                  <option value="-1" <?= ((string)($landing_page['REGISTERED_TEMPLATE'] ?? '-1') === '-1') ? 'selected' : '' ?>>Select</option>
                  <?php foreach ($all_templates as $template) : ?>
                    <option value="<?php echo $template['TEMPLATE_ID']; ?>" <?= ((string)($landing_page['REGISTERED_TEMPLATE'] ?? '') === (string)$template['TEMPLATE_ID']) ? 'selected' : '' ?>>
                      <?php echo htmlspecialchars($template['TEMPLATE_NAME']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-6 mt-2">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="PAYMENT_TEMPLATE">Template on Payment <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="PAYMENT_TEMPLATE" id="PAYMENT_TEMPLATE" class="form-control">
                  <option value="-1" <?= ((int)($landing_page['PAYMENT_TEMPLATE'] ?? -1) === -1) ? 'selected' : '' ?>>Select</option>
                  <?php foreach ($all_templates as $template) : ?>
                    <option value="<?php echo $template['TEMPLATE_ID']; ?>" <?= ((int)($landing_page['PAYMENT_TEMPLATE'] ?? 0) === (int)$template['TEMPLATE_ID']) ? 'selected' : '' ?>>
                      <?php echo htmlspecialchars($template['TEMPLATE_NAME']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Dimensions -->
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="BANNER_IMAGE_HEIGHT">Banner Image Height (px) <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input class="form-control" id="BANNER_IMAGE_HEIGHT" name="BANNER_IMAGE_HEIGHT" type="number" value="<?= htmlspecialchars($landing_page['BANNER_IMAGE_HEIGHT'] ?? 500) ?>" />
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="BANNER_IMAGE_WIDTH">Banner Image Width (px) <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input class="form-control" id="BANNER_IMAGE_WIDTH" name="BANNER_IMAGE_WIDTH" type="number" value="<?= htmlspecialchars($landing_page['BANNER_IMAGE_WIDTH'] ?? 1200) ?>" />
              </div>
            </div>
          </div>
        </div>

        <!-- Page Content -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Page Content</h5></div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_MAIN_CONTENT">Landing Page Main Content <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <textarea class="form-control js-richtext" name="LANDING_PAGE_MAIN_CONTENT" id="LANDING_PAGE_MAIN_CONTENT" placeholder="Main content" required><?= htmlspecialchars($landing_page['LANDING_PAGE_MAIN_CONTENT'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_LEFT_CONTENT">Landing Page Left Content <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <textarea class="form-control js-richtext" name="LANDING_PAGE_LEFT_CONTENT" id="LANDING_PAGE_LEFT_CONTENT" placeholder="Left content" required><?= htmlspecialchars($landing_page['LANDING_PAGE_LEFT_CONTENT'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_RIGHT_CONTENT">Landing Page Right Content <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <textarea class="form-control js-richtext" name="LANDING_PAGE_RIGHT_CONTENT" id="LANDING_PAGE_RIGHT_CONTENT" placeholder="Right content" required><?= htmlspecialchars($landing_page['LANDING_PAGE_RIGHT_CONTENT'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- VIP / Thanks (Type 1 keeps legacy; Thanks is common) -->
        <div data-section="type1">
          <div class="clearfix mb-2"><h5 class="text-warning h5">VIP Experience (Basic)</h5></div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="LANDING_PAGE_VIP_EXPERIENCE_CONTENT">VIP Experience Content (Before Link) <span class="text-danger">*</span></label>
                <div class="col-md-10">
                  <textarea class="form-control js-richtext" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT" id="LANDING_PAGE_VIP_EXPERIENCE_CONTENT" placeholder="Landing Page VIP Experience Content"><?= htmlspecialchars($landing_page['LANDING_PAGE_VIP_EXPERIENCE_CONTENT'] ?? '') ?></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS">VIP Experience Content Steps <span class="text-danger">*</span></label>
                <div class="col-md-10">
                  <textarea class="form-control js-richtext" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS" id="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS" placeholder="Landing Page VIP Experience Content Steps"><?= htmlspecialchars($landing_page['LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS'] ?? '') ?></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Thanks (common) -->
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_THANKS_CONTENT">Landing Page Thanks Content <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <textarea class="form-control js-richtext" name="LANDING_PAGE_THANKS_CONTENT" id="LANDING_PAGE_THANKS_CONTENT" placeholder="Landing Page Thanks Content" required><?= htmlspecialchars($landing_page['LANDING_PAGE_THANKS_CONTENT'] ?? '') ?></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Legacy Fees -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Legacy Fees (optional)</h5></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="LANDING_PAGE_FEE">Fee</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="LANDING_PAGE_FEE" id="LANDING_PAGE_FEE" placeholder="e.g. 3102.00" value="<?= htmlspecialchars($landing_page['LANDING_PAGE_FEE'] ?? '') ?>">
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="LANDING_PAGE_VIP_FEE">VIP Fee</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="LANDING_PAGE_VIP_FEE" id="LANDING_PAGE_VIP_FEE" placeholder="e.g. 0.00" value="<?= htmlspecialchars($landing_page['LANDING_PAGE_VIP_FEE'] ?? '') ?>">
              </div>
            </div>
          </div>
        </div>

        <!-- ===== TYPE 2 ONLY (Advanced – new template fields) ===== -->
        <div data-section="type2">
          <!-- FAQs -->
          <div class="clearfix mb-2"><h5 class="text-warning h5">FAQs</h5></div>
          <div id="faq-repeater" class="mb-3">
            <?php
              $faqItems = [];
              if (!empty($landing_page['FAQ_JSON'])) {
                $decoded = json_decode($landing_page['FAQ_JSON'], true);
                if (is_array($decoded)) { $faqItems = $decoded; }
              }
              if (empty($faqItems)) {
                $faqItems = [
                  ['q' => '', 'a' => ''],
                  ['q' => '', 'a' => ''],
                  ['q' => '', 'a' => ''],
                ];
              }
              $index = 1;
              foreach ($faqItems as $fa):
            ?>
            <div class="faq-item border rounded p-2 mb-2">
              <div class="form-group row">
                <label class="col-form-label col-md-2">Question #<?= $index ?></label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="FAQ_QUESTIONS[]" value="<?= htmlspecialchars($fa['q'] ?? '') ?>" placeholder="Enter question">
                </div>
              </div>
              <div class="form-group row mb-0">
                <label class="col-form-label col-md-2">Answer</label>
                <div class="col-md-10">
                  <textarea class="form-control" name="FAQ_ANSWERS[]" rows="2" placeholder="Enter answer"><?= htmlspecialchars($fa['a'] ?? '') ?></textarea>
                </div>
              </div>
            </div>
            <?php $index++; endforeach; ?>
          </div>

          <div class="d-flex gap-2 mb-4">
            <button type="button" id="faq-add" class="btn btn-outline-primary btn-sm">+ Add FAQ</button>
            <button type="button" id="faq-collapse" class="btn btn-outline-secondary btn-sm">Collapse all</button>
          </div>

          <template id="faq-item-template">
            <div class="faq-item border rounded p-2 mb-2">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <strong class="small text-muted faq-number">Question #N</strong>
                <button type="button" class="btn btn-link text-danger btn-sm faq-remove" title="Remove this FAQ">Remove</button>
              </div>
              <div class="form-group row">
                <label class="col-form-label col-md-2">Question</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" name="FAQ_QUESTIONS[]" placeholder="Enter question">
                </div>
              </div>
              <div class="form-group row mb-0">
                <label class="col-form-label col-md-2">Answer</label>
                <div class="col-md-10">
                  <textarea class="form-control" name="FAQ_ANSWERS[]" rows="2" placeholder="Enter answer"></textarea>
                </div>
              </div>
            </div>
          </template>

          <!-- Testimonials -->
          <div class="clearfix mb-2"><h5 class="text-warning h5">Testimonials</h5></div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="TESTIMONIALS_IMAGES">Testimonial Images (comma-separated paths)</label>
                <div class="col-md-10">
                  <input class="form-control" id="TESTIMONIALS_IMAGES" type="text" name="TESTIMONIALS_IMAGES" placeholder="/uploads/img1.jpg, /uploads/img2.jpg" value="<?= htmlspecialchars($landing_page['TESTIMONIALS_IMAGES'] ?? '') ?>" />
                  <small class="text-muted d-block">These render under the embedded YouTube Shorts.</small>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="TESTIMONIALS_VIDEOS">Testimonial Videos (comma-separated URLs)</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="TESTIMONIALS_VIDEOS" name="TESTIMONIALS_VIDEOS" rows="2" placeholder="https://youtube.com/shorts/.., https://youtube.com/shorts/..."><?= htmlspecialchars($landing_page['TESTIMONIALS_VIDEOS'] ?? '') ?></textarea>
                  <small class="text-muted">If empty, the page uses the default 5 Shorts from the design.</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Coach / Trainer -->
          <div class="clearfix mb-2"><h5 class="text-warning h5">Coach / Trainer</h5></div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="COACH_NAME">Coach Name</label>
                <div class="col-md-8">
                  <input class="form-control" id="COACH_NAME" type="text" name="COACH_NAME" value="<?= htmlspecialchars($landing_page['COACH_NAME'] ?? '') ?>" placeholder="Barinderjeet Kaur" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="COACH_SUBTITLE">Coach Sub-Title</label>
                <div class="col-md-8">
                  <input class="form-control" id="COACH_SUBTITLE" type="text" name="COACH_SUBTITLE" value="<?= htmlspecialchars($landing_page['COACH_SUBTITLE'] ?? '') ?>" placeholder="Founder, Empower Your Destiny · Human Behaviour Specialist" />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="COACH_PHOTO">Coach Photo (URL)</label>
                <div class="col-md-10">
                  <input class="form-control" id="COACH_PHOTO" type="text" name="COACH_PHOTO" value="<?= htmlspecialchars($landing_page['COACH_PHOTO'] ?? '') ?>" placeholder="https://.../photo.jpg" />
                  <?php if (!empty($landing_page['COACH_PHOTO'])): ?>
                    <small class="d-block mt-1"><a href="<?= $landing_page['COACH_PHOTO'] ?>" target="_blank">View Current Photo</a></small>
                  <?php endif; ?>
                  <small class="text-muted">If you upload via file on Add page, controller saves the path here.</small>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="COACH_BIO_SHORT">Coach Bio Intro (short)</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="COACH_BIO_SHORT" name="COACH_BIO_SHORT" rows="3" placeholder="Short intro paragraph"><?= htmlspecialchars($landing_page['COACH_BIO_SHORT'] ?? '') ?></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="COACH_BIO_LONG">Coach Bio Details</label>
                <div class="col-md-10">
                  <textarea class="form-control js-richtext" id="COACH_BIO_LONG" name="COACH_BIO_LONG" rows="4" placeholder="Longer details, modalities, recognitions"><?= htmlspecialchars($landing_page['COACH_BIO_LONG'] ?? '') ?></textarea>
                  <!-- <textarea class="form-control js-richtext" name="LANDING_PAGE_THANKS_CONTENT" id="LANDING_PAGE_THANKS_CONTENT" placeholder="Landing Page Thanks Content" required><?= htmlspecialchars($landing_page['LANDING_PAGE_THANKS_CONTENT'] ?? '') ?></textarea> -->
                </div>
              </div>
            </div>
          </div>

          <!-- Pricing & Payments -->
          <div class="clearfix mb-2"><h5 class="text-warning h5">Pricing & Payments</h5></div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="CURRENCY">Currency</label>
                <div class="col-md-7">
                  <input class="form-control" id="CURRENCY" type="text" name="CURRENCY" value="<?= htmlspecialchars($landing_page['CURRENCY'] ?? 'aud') ?>" placeholder="aud" />
                  <small class="text-muted">Lowercase ISO currency (front-end shows AUD$ prefix).</small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_FIRST_TRAINING">1st Training Price</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_FIRST_TRAINING" type="number" step="0.01" name="PRICE_FIRST_TRAINING" value="<?= htmlspecialchars($landing_page['PRICE_FIRST_TRAINING'] ?? '') ?>" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_SECOND_TRAINING">2nd Training Price</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_SECOND_TRAINING" type="number" step="0.01" name="PRICE_SECOND_TRAINING" value="<?= htmlspecialchars($landing_page['PRICE_SECOND_TRAINING'] ?? '') ?>" />
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_BUNDLE_TOTAL">Bundle Total</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_BUNDLE_TOTAL" type="number" step="0.01" name="PRICE_BUNDLE_TOTAL" value="<?= htmlspecialchars($landing_page['PRICE_BUNDLE_TOTAL'] ?? '') ?>" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_DEPOSIT">Deposit Amount</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_DEPOSIT" type="number" step="0.01" name="PRICE_DEPOSIT" value="<?= htmlspecialchars($landing_page['PRICE_DEPOSIT'] ?? '') ?>" />
                </div>
              </div>
            </div>
          </div>

          <!-- Help / Connect -->
          <div class="clearfix mb-2"><h5 class="text-warning h5">Help / Connect Links</h5></div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="WHATSAPP_NUMBER">WhatsApp Number (E.164)</label>
                <div class="col-md-8">
                  <input class="form-control" id="WHATSAPP_NUMBER" type="text" name="WHATSAPP_NUMBER" placeholder="+61426886501" value="<?= htmlspecialchars($landing_page['WHATSAPP_NUMBER'] ?? '') ?>" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="WHATSAPP_TEXT">WhatsApp Prefill Text</label>
                <div class="col-md-8">
                  <input class="form-control" id="WHATSAPP_TEXT" type="text" name="WHATSAPP_TEXT" placeholder="Hi ..., I'm interested in the Destiny Duo Certification." value="<?= htmlspecialchars($landing_page['WHATSAPP_TEXT'] ?? '') ?>" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="MESSENGER_PAGE_URL">Messenger (Page) URL</label>
                <div class="col-md-8">
                  <input class="form-control" id="MESSENGER_PAGE_URL" type="url" name="MESSENGER_PAGE_URL" placeholder="https://m.me/empoweryourdestiny" value="<?= htmlspecialchars($landing_page['MESSENGER_PAGE_URL'] ?? '') ?>" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="MESSENGER_TRAINER_URL">Messenger (Trainer) URL</label>
                <div class="col-md-8">
                  <input class="form-control" id="MESSENGER_TRAINER_URL" type="url" name="MESSENGER_TRAINER_URL" placeholder="https://m.me/barinderjeet.kaur.39" value="<?= htmlspecialchars($landing_page['MESSENGER_TRAINER_URL'] ?? '') ?>" />
                </div>
              </div>
            </div>
          </div>

          <!-- Optional Copy Tweaks -->
          <div class="clearfix mb-2"><h5 class="text-warning h5">Optional Copy Tweaks</h5></div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="VIP_CARD_TEXT">VIP Upgrade Text (card)</label>
                <div class="col-md-10">
                  <input class="form-control" id="VIP_CARD_TEXT" type="text" name="VIP_CARD_TEXT" placeholder="Personalized mentorship, business tools, exclusive support" value="<?= htmlspecialchars($landing_page['VIP_CARD_TEXT'] ?? '') ?>" />
                </div>
              </div>
            </div>
          </div>
        </div> <!-- /type2 only -->

        <!-- Submit -->
        <div class="form-group row">
          <div class="col-md-6 offset-md-2">
            <a class="dropdown-item mb-2" target="_blank" href="<?php echo base_url('LandingPageController/viewLandingPagePreview/' . $landing_page['LANDING_PAGE_ID']); ?>"><i class="dw dw-eye"></i> PREVIEW</a>
            <button type="submit" class="btn btn-warning">Update Landing Page</button>
          </div>
        </div>
      </form>
    </div>
    <!-- Default Basic Forms End -->
  </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<!-- Footer typically contains </body></html> in your layout -->

<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>

<script>
(function () {
  function initEditors() {
    if (!window.CKEDITOR) return;

    var areas = document.querySelectorAll('textarea.js-richtext');
    areas.forEach(function (ta) {
      // Avoid double init
      if (ta.getAttribute('data-cke-initialized') === '1') return;

      CKEDITOR.replace(ta, {
        height: 280,
        // IMPORTANT: allows full HTML (use carefully; sanitize on backend)
        allowedContent: true,
        extraAllowedContent: '*(*);*{*}[*]',
        removeButtons: '',
        toolbar: [
          { name: 'document', items: ['Source','-','Preview','Print'] },
          { name: 'clipboard', items: ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'] },
          { name: 'editing', items: ['Find','Replace','-','SelectAll'] },
          { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','RemoveFormat'] },
          { name: 'paragraph', items: ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','JustifyLeft','JustifyCenter','JustifyRight'] },
          { name: 'links', items: ['Link','Unlink'] },
          { name: 'insert', items: ['Image','Table','HorizontalRule','SpecialChar'] },
          { name: 'styles', items: ['Styles','Format','Font','FontSize'] },
          { name: 'colors', items: ['TextColor','BGColor'] },
          { name: 'tools', items: ['Maximize'] }
        ]
      });

      ta.setAttribute('data-cke-initialized', '1');
    });
  }

  // Init once DOM ready
  document.addEventListener('DOMContentLoaded', initEditors);

  // If you use AJAX-loaded forms / modals, call initEditors again after content loads
  window.initEditors = initEditors;

  // Ensure editor content is pushed back into textarea on submit
  document.addEventListener('submit', function () {
    if (window.CKEDITOR) {
      for (var i in CKEDITOR.instances) {
        if (CKEDITOR.instances.hasOwnProperty(i)) {
          CKEDITOR.instances[i].updateElement();
        }
      }
    }
  }, true);
})();
</script>


<script>
/* ===== Toggle fields by Type (1=Basic, 2=Advanced) ===== */
(function(){
  const typeSel   = document.getElementById('typeSelector');
  const typeHidden= document.getElementById('LANDING_PAGE_TYPE');

  function setRequiredByType(activeType){
    document.querySelectorAll('[data-required-when]').forEach(el => {
      try { el.required = false; } catch(e){}
    });
    document.querySelectorAll('[data-required-when]').forEach(el => {
      const list = (el.getAttribute('data-required-when') || '').split(',').map(s => s.trim());
      if (list.includes(String(activeType))) {
        try { el.required = true; } catch(e){}
      }
    });
  }

  function toggleSections(){
    const t = typeSel ? typeSel.value : (typeHidden ? typeHidden.value : '1');
    if (typeHidden) typeHidden.value = t;
    document.querySelectorAll('[data-section="type1"]').forEach(s => s.style.display = (t === '1' ? '' : 'none'));
    document.querySelectorAll('[data-section="type2"]').forEach(s => s.style.display = (t === '2' ? '' : 'none'));
    setRequiredByType(t);
  }

  if (typeSel) typeSel.addEventListener('change', toggleSections);
  toggleSections(); // init on load
})();
</script>

<script>
/* ===== FAQ repeater (Type 2) ===== */
(function(){
  const list   = document.getElementById('faq-repeater');
  const addBtn = document.getElementById('faq-add');
  const tpl    = document.getElementById('faq-item-template');
  const collapseBtn = document.getElementById('faq-collapse');

  if (!list || !tpl) return;

  function renumber(){
    const items = list.querySelectorAll('.faq-item');
    items.forEach((item, idx) => {
      const label = item.querySelector('.faq-number');
      if (label) label.textContent = 'Question #' + (idx + 1);
    });
  }

  function addItem(){
    const node = document.importNode(tpl.content, true);
    list.appendChild(node);
    renumber();
  }

  if (addBtn) addBtn.addEventListener('click', addItem);

  list.addEventListener('click', function(e){
    if (e.target.classList.contains('faq-remove')) {
      const wrap = e.target.closest('.faq-item');
      if (!wrap) return;
      if (list.querySelectorAll('.faq-item').length <= 1) return;
      wrap.remove();
      renumber();
    }
  });

  let collapsed = false;
  if (collapseBtn) {
    collapseBtn.addEventListener('click', function(){
      collapsed = !collapsed;
      const inner = list.querySelectorAll('.faq-item .form-group');
      inner.forEach(el => el.style.display = collapsed ? 'none' : '');
      collapseBtn.textContent = collapsed ? 'Expand all' : 'Collapse all';
    });
  }
})();
</script>
