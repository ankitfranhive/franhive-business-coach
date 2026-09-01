<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('includes/header'); ?>

<script src="<?php echo base_url('vendor/tinymce/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded', function () {
    // Initialize TinyMCE only on textareas that should be rich text
    tinymce.init({
      selector: 'textarea.tinymce',
      plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
      tinycomments_mode: 'embedded',
      tinycomments_author: 'Author name',
      branding: false,
      setup: function (editor) {
        editor.on('change', function () {
          tinymce.triggerSave(); // Keep underlying textarea in sync
        });
      }
    });
  });
</script>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
  <div class="pd-ltr-20 xs-pd-20-10">
    <div class="page-header">
      <div class="row">
        <div class="col-md-6 col-sm-12">
          <div class="title">
            <h4>Add A Landing Page</h4>
          </div>
          <p class="mb-0 text-muted small">
            Choose a type and fill the fields below. Type 1 = Basic (existing template), Type 2 = Advanced (new Destiny Duo template).
          </p>
        </div>
        <div class="col-md-6 col-sm-12 text-right">
          <a class="btn btn-warning" href="<?= base_url('/landing-pages'); ?>" role="button">
            Back To Pages List
          </a>
        </div>
      </div>
    </div>

    <div class="pd-20 card-box mb-30">
      <form action="<?= base_url('LandingPageController/createNewLandingPage') ?>" method="post" enctype="multipart/form-data">
        <!-- TYPE SELECT + hidden flag actually posted -->
        <input type="hidden" name="LANDING_PAGE_TYPE" id="LANDING_PAGE_TYPE" value="1" />
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="typeSelector">Landing Page Type <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select id="typeSelector" class="form-control" aria-describedby="typeHelp">
                  <option value="1" selected>1 — Basic</option>
                  <option value="2">2 — Advanced</option>
                </select>
                <small id="typeHelp" class="text-muted">Switching type will show/hide the extra fields accordingly.</small>
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
                <input class="form-control" id="LANDING_PAGE_NAME" type="text" name="LANDING_PAGE_NAME" placeholder="Title" required />
              </div>
            </div>
          </div>
        </div>

        <!-- Media (Banner is common; Share Image is Type 2 only) -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Media</h5></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="BANNER_IMAGE">Banner Image <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input class="form-control" id="BANNER_IMAGE" name="BANNER_IMAGE" type="file" accept="image/*" data-required-when="1,2" />
                <small class="text-muted">Shown in hero (Type 2 uses it on the right side of split hero).</small>
              </div>
            </div>
          </div>

          <!-- Type 2: Share image -->
          <div class="col-md-6" data-section="type2">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="SHARE_IMAGE">Share Image (OG/Twitter)</label>
              <div class="col-md-8">
                <input class="form-control" id="SHARE_IMAGE" name="SHARE_IMAGE" type="file" accept="image/*" />
                <small class="text-muted">If empty, we’ll fall back to the Banner Image.</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Emails / Templates (common) -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Emails / Templates</h5></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="CC_EMAIL">CC Email <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <input class="form-control" id="CC_EMAIL" type="email" name="CC_EMAIL" placeholder="Email" data-required-when="1,2" />
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="EMAIL_TEMPLATE">Email Template (General) <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="EMAIL_TEMPLATE" id="EMAIL_TEMPLATE" class="form-control" data-required-when="1,2">
                  <option value="-1">Select</option>
                  <?php foreach ($all_templates as $template) : ?>
                    <option value="<?php echo $template['TEMPLATE_ID']; ?>"><?php echo $template['TEMPLATE_NAME']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="REGISTERED_TEMPLATE">Template on Registration <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="REGISTERED_TEMPLATE" id="REGISTERED_TEMPLATE" class="form-control" data-required-when="1,2">
                  <option value="-1">Select</option>
                  <?php foreach ($all_templates as $template) : ?>
                    <option value="<?php echo $template['TEMPLATE_ID']; ?>"><?php echo $template['TEMPLATE_NAME']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="PAYMENT_TEMPLATE">Template on Payment <span class="text-danger">*</span></label>
              <div class="col-md-8">
                <select name="PAYMENT_TEMPLATE" id="PAYMENT_TEMPLATE" class="form-control" data-required-when="1,2">
                  <option value="-1">Select</option>
                  <?php foreach ($all_templates as $template) : ?>
                    <option value="<?php echo $template['TEMPLATE_ID']; ?>"><?php echo $template['TEMPLATE_NAME']; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Page Content (common) -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Page Content</h5></div>

        <!-- Page Main Content  -->
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_LEFT_CONTENT">Landing Page Main Content <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <textarea class="form-control tinymce" name="LANDING_PAGE_LEFT_CONTENT" id="LANDING_PAGE_LEFT_CONTENT" placeholder="Left content" required></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Page Side Content  -->
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_RIGHT_CONTENT">Landing Page Side Content <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <textarea class="form-control tinymce" name="LANDING_PAGE_RIGHT_CONTENT" id="LANDING_PAGE_RIGHT_CONTENT" placeholder="Right content" required></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Thanks Content -->
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label class="col-form-label col-md-2" for="LANDING_PAGE_THANKS_CONTENT">Landing Page Thanks Content <span class="text-danger">*</span></label>
              <div class="col-md-10">
                <textarea class="form-control tinymce" name="LANDING_PAGE_THANKS_CONTENT" id="LANDING_PAGE_THANKS_CONTENT" placeholder="Thanks content" required></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- ===== TYPE 1 ONLY (Basic – legacy VIP content fields) ===== -->
        <div data-section="type1">
          <div class="clearfix mb-2"><h5 class="text-warning h5">VIP Experience (Basic)</h5></div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="LANDING_PAGE_VIP_EXPERIENCE_CONTENT">VIP Experience Content (Before Link) <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input class="form-control" id="LANDING_PAGE_VIP_EXPERIENCE_CONTENT" type="text" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT" placeholder="VIP experience copy" data-required-when="1" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS">VIP Experience Content Steps <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input class="form-control" id="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS" type="text" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS" placeholder="VIP steps" data-required-when="1" />
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ===== TYPE 2 ONLY (Advanced – new template fields) ===== -->
        <div data-section="type2">
          <!-- FAQ -->
          <div class="clearfix mb-2"><h5 class="text-warning h5">FAQs</h5></div>
          <div id="faq-repeater" class="mb-3">
            <div class="faq-item border rounded p-2 mb-2">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="faq_q1">Question #1</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" id="faq_q1" name="FAQ_QUESTIONS[]" placeholder="Enter question">
                </div>
              </div>
              <div class="form-group row mb-0">
                <label class="col-form-label col-md-2" for="faq_a1">Answer</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="faq_a1" name="FAQ_ANSWERS[]" rows="2" placeholder="Enter answer"></textarea>
                </div>
              </div>
            </div>

            <div class="faq-item border rounded p-2 mb-2">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="faq_q2">Question #2</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" id="faq_q2" name="FAQ_QUESTIONS[]" placeholder="Enter question">
                </div>
              </div>
              <div class="form-group row mb-0">
                <label class="col-form-label col-md-2" for="faq_a2">Answer</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="faq_a2" name="FAQ_ANSWERS[]" rows="2" placeholder="Enter answer"></textarea>
                </div>
              </div>
            </div>

            <div class="faq-item border rounded p-2 mb-2">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="faq_q3">Question #3</label>
                <div class="col-md-10">
                  <input type="text" class="form-control" id="faq_q3" name="FAQ_QUESTIONS[]" placeholder="Enter question">
                </div>
              </div>
              <div class="form-group row mb-0">
                <label class="col-form-label col-md-2" for="faq_a3">Answer</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="faq_a3" name="FAQ_ANSWERS[]" rows="2" placeholder="Enter answer"></textarea>
                </div>
              </div>
            </div>
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
                  <input class="form-control" id="TESTIMONIALS_IMAGES" type="text" name="TESTIMONIALS_IMAGES" placeholder="/uploads/img1.jpg, /uploads/img2.jpg" />
                  <small class="text-muted d-block">These render under the embedded YouTube Shorts.</small>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="TESTIMONIALS_VIDEOS">Testimonial Videos (comma-separated URLs)</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="TESTIMONIALS_VIDEOS" name="TESTIMONIALS_VIDEOS" rows="2" placeholder="https://youtube.com/shorts/.., https://youtube.com/shorts/..."></textarea>
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
                  <input class="form-control" id="COACH_NAME" type="text" name="COACH_NAME" placeholder="Barinderjeet Kaur" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="COACH_SUBTITLE">Coach Sub-Title</label>
                <div class="col-md-8">
                  <input class="form-control" id="COACH_SUBTITLE" type="text" name="COACH_SUBTITLE" placeholder="Founder, Empower Your Destiny · Human Behaviour Specialist" />
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="COACH_PHOTO">Coach Photo (URL or upload)</label>
                <div class="col-md-10">
                  <input class="form-control mb-2" id="COACH_PHOTO" type="text" name="COACH_PHOTO" placeholder="https://.../photo.jpg" />
                  <input class="form-control" type="file" name="COACH_PHOTO_FILE" accept="image/*" />
                  <small class="text-muted">If a file is uploaded, save path into COACH_PHOTO.</small>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="COACH_BIO_SHORT">Coach Bio Intro (short)</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="COACH_BIO_SHORT" name="COACH_BIO_SHORT" rows="3" placeholder="Short intro paragraph"></textarea>
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group row">
                <label class="col-form-label col-md-2" for="COACH_BIO_LONG">Coach Bio Details</label>
                <div class="col-md-10">
                  <textarea class="form-control" id="COACH_BIO_LONG" name="COACH_BIO_LONG" rows="4" placeholder="Longer details, modalities, recognitions"></textarea>
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
                  <input class="form-control" id="CURRENCY" type="text" name="CURRENCY" value="aud" placeholder="aud" />
                  <small class="text-muted">Lowercase ISO currency (front-end shows AUD$ prefix).</small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_FIRST_TRAINING">1st Training Price</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_FIRST_TRAINING" type="number" step="0.01" name="PRICE_FIRST_TRAINING" value="" />
                  <small class="text-muted">Shown as item price and data-amount-cents.</small>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_SECOND_TRAINING">2nd Training Price</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_SECOND_TRAINING" type="number" step="0.01" name="PRICE_SECOND_TRAINING" value="" />
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_BUNDLE_TOTAL">Bundle Total</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_BUNDLE_TOTAL" type="number" step="0.01" name="PRICE_BUNDLE_TOTAL" value="" />
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group row">
                <label class="col-form-label col-md-5" for="PRICE_DEPOSIT">Deposit Amount</label>
                <div class="col-md-7">
                  <input class="form-control" id="PRICE_DEPOSIT" type="number" step="0.01" name="PRICE_DEPOSIT" value="" />
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
                  <input class="form-control" id="WHATSAPP_NUMBER" type="text" name="WHATSAPP_NUMBER" placeholder="+61426886501" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="WHATSAPP_TEXT">WhatsApp Prefill Text</label>
                <div class="col-md-8">
                  <input class="form-control" id="WHATSAPP_TEXT" type="text" name="WHATSAPP_TEXT" placeholder="Hi ..., I'm interested in the Destiny Duo Certification." />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="MESSENGER_PAGE_URL">Messenger (Page) URL</label>
                <div class="col-md-8">
                  <input class="form-control" id="MESSENGER_PAGE_URL" type="url" name="MESSENGER_PAGE_URL" placeholder="https://m.me/empoweryourdestiny" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row">
                <label class="col-form-label col-md-4" for="MESSENGER_TRAINER_URL">Messenger (Trainer) URL</label>
                <div class="col-md-8">
                  <input class="form-control" id="MESSENGER_TRAINER_URL" type="url" name="MESSENGER_TRAINER_URL" placeholder="https://m.me/barinderjeet.kaur.39" />
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
                  <input class="form-control" id="VIP_CARD_TEXT" type="text" name="VIP_CARD_TEXT" placeholder="Personalized mentorship, business tools, exclusive support" />
                </div>
              </div>
            </div>
          </div>
        </div> <!-- /type2 only -->

        <!-- Legacy Fees (common fields preserved) -->
        <div class="clearfix mb-2"><h5 class="text-warning h5">Legacy Fees (optional)</h5></div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="LANDING_PAGE_FEE">Fee</label>
              <div class="col-md-8">
                <input class="form-control" id="LANDING_PAGE_FEE" type="number" step="0.01" name="LANDING_PAGE_FEE" placeholder="e.g. 3102.00" />
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row">
              <label class="col-form-label col-md-4" for="LANDING_PAGE_VIP_FEE">VIP Fee</label>
              <div class="col-md-8">
                <input class="form-control" id="LANDING_PAGE_VIP_FEE" type="number" step="0.01" name="LANDING_PAGE_VIP_FEE" placeholder="e.g. 0.00" />
              </div>
            </div>
          </div>
        </div>

        <!-- SUBMIT -->
        <div class="form-group row">
          <div class="col-md-6 offset-md-2">
            <button type="submit" class="btn btn-warning">Add Landing Page</button>
          </div>
        </div>
      </form>
    </div>
    <!-- Default Basic Forms End -->
  </div>
</div>

<?php $this->load->view('includes/footer'); ?>
<!-- NOTE: Footer should close </body> and </html> if your template is set up that way. -->

<script>
/* ===== Toggle fields by Type (1=Basic, 2=Advanced) ===== */
(function(){
  const typeSel = document.getElementById('typeSelector');
  const typeHidden = document.getElementById('LANDING_PAGE_TYPE');

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
    const t = typeSel.value;
    typeHidden.value = t;
    document.querySelectorAll('[data-section="type1"]').forEach(s => s.style.display = (t === '1' ? '' : 'none'));
    document.querySelectorAll('[data-section="type2"]').forEach(s => s.style.display = (t === '2' ? '' : 'none'));
    setRequiredByType(t);
  }

  if (typeSel) {
    typeSel.addEventListener('change', toggleSections);
    toggleSections(); // init on load
  }
})();
</script>

<script>
/* ===== FAQ repeater (only visible for Type 2) ===== */
(function(){
  const list   = document.getElementById('faq-repeater');
  const addBtn = document.getElementById('faq-add');
  const tpl    = document.getElementById('faq-item-template');
  const collapseBtn = document.getElementById('faq-collapse');

  if (!list || !addBtn || !tpl) return;

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

  addBtn.addEventListener('click', addItem);

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
