<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>
<script src="<?php echo base_url('vendor/tinymce/tinymce/tinymce.min.js'); ?>"></script>
<script type="text/javascript">
   $(function() {
      tinymce.init({
         selector: 'textarea',
         plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
         toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
         tinycomments_mode: 'embedded',
         tinycomments_author: 'Author name',
         branding: false,
         setup: function(editor) {
            editor.on('change', function() {
               tinymce.triggerSave(); // Update the textarea with TinyMCE content
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
                        <h4>Edit Landing Page</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/landing-pages'); ?>" role="button">
                        Back To Pages List
                    </a>
                    <a class="btn btn-warning" href="<?php echo base_url('video-page-edit/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>" role="button">
                        Videos
                    </a>
                </div>
                
            </div>
        </div>
        <div class="pd-20 card-box mb-30">
            <div class="clearfix">
                <div class="pull-left">
                    <!-- <h4 class="text-blue h4">Add A new Question</h4> -->
                </div>
            </div>
            <form action="<?= base_url('LandingPageController/updateLandingPage') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $landing_page['LANDING_PAGE_ID'] ?>">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page Title <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                
                                <input class="form-control" type="text" name="LANDING_PAGE_NAME" placeholder="Title" value="<?= $landing_page['LANDING_PAGE_NAME'] ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Landing Page URL <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <a class="dropdown-item" target="_blank" href="<?php echo base_url('LandingPageController/viewLandingPagePreview/' . $landing_page['LANDING_PAGE_ID'] . ''); ?>"><i class="dw dw-eye"></i> PREVIEW</a>

                                <!-- <input class="form-control" type="text" name="LANDING_PAGE_URL" placeholder="URL" value="<?= $landing_page['LANDING_PAGE_URL'] ?>" /> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4" for="BANNER_IMAGE">Banner Image<span style="color: red;">*</span></label>
                            <div class="col-md-8">

                            <input class="form-control" value="<?= $landing_page['BANNER_IMAGE'] ?>" name="BANNER_IMAGE" type="file" accept="image/*" />
                            <a href="<?= $landing_page['BANNER_IMAGE'] ?>" target="_blank">View Current Image</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="CC_EMAIL" placeholder="Email" value="<?= $landing_page['CC_EMAIL'] ?>" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4" for="BANNER_IMAGE_HEIGHT">Banner Image Height (PX)<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input class="form-control" value="<?= $landing_page['BANNER_IMAGE_HEIGHT'] ?>" name="BANNER_IMAGE_HEIGHT" type="number"  />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4" for="BANNER_IMAGE_WIDTH">Banner Image Width (PX)<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                            <input class="form-control" value="<?= $landing_page['BANNER_IMAGE_WIDTH'] ?>" name="BANNER_IMAGE_WIDTH" type="number"  />

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email Template on Registration<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="REGISTERED_TEMPLATE" id="REGISTERED_TEMPLATE" class="form-control" onchange="">
                                    <option value="-1" <?= ($landing_page['REGISTERED_TEMPLATE'] == -1) ? 'selected' : '' ?>>Select</option>
                                    <?php foreach ($all_templates as $template) : ?>
                                        <option value="<?php echo $template['TEMPLATE_ID']; ?>" <?= ($landing_page['REGISTERED_TEMPLATE'] == $template['TEMPLATE_ID']) ? 'selected' : '' ?>><?php echo $template['TEMPLATE_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email Template on Payment <span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="PAMENT_TEMPLATE" id="PAMENT_TEMPLATE" class="form-control" onchange="">
                                    <option value="-1" <?= ($landing_page['PAMENT_TEMPLATE'] == -1) ? 'selected' : '' ?>>Select</option>
                                    <?php foreach ($all_templates as $template) : ?>
                                        <option value="<?php echo $template['TEMPLATE_ID']; ?>" <?= ($landing_page['PAMENT_TEMPLATE'] == $template['TEMPLATE_ID']) ? 'selected' : '' ?>><?php echo $template['TEMPLATE_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Landing Page Main Content <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="LANDING_PAGE_LEFT_CONTENT" id="ckeditor" placeholder="Left content" required>
                                    <?= htmlspecialchars($landing_page['LANDING_PAGE_LEFT_CONTENT']) ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Landing Page Side Content <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="LANDING_PAGE_RIGHT_CONTENT" id="ckeditor" placeholder="Right content" required>
                                        <?= htmlspecialchars($landing_page['LANDING_PAGE_RIGHT_CONTENT']) ?>
                                    </textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Landing Page VIP Experience Content (Before Link) <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT" id="ckeditor" placeholder="Landing Page VIP Experience Content" required>
                                    <?= htmlspecialchars($landing_page['LANDING_PAGE_VIP_EXPERIENCE_CONTENT']) ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Landing Page VIP Experience Content Steps <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS" id="ckeditor" placeholder="Landing Page VIP Experience Content Steps" required>
                                    <?= htmlspecialchars($landing_page['LANDING_PAGE_VIP_EXPERIENCE_CONTENT_STEPS']) ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Landing Page Thanks Content <span style="color: red;">*</span></label>
                            <div class="col-md-10">
                                <textarea class="form-control" name="LANDING_PAGE_THANKS_CONTENT" id="ckeditor" placeholder="Landing Page Thanks Content" required>
                                    <?= htmlspecialchars($landing_page['LANDING_PAGE_THANKS_CONTENT']) ?>
                                </textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Fee </span></label>
                            <div class="col-md-10">
                            <input type="text" class="form-control" name="LANDING_PAGE_FEE" id="ckeditor" placeholder="Fee"  
                       value="<?= isset($landing_page['LANDING_PAGE_FEE']) ? htmlspecialchars($landing_page['LANDING_PAGE_FEE']) : '' ?>">

                                   
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">VIP Fee </label>
                            <div class="col-md-10">
                            <input type="text" class="form-control" name="LANDING_PAGE_VIP_FEE" id="ckeditor" placeholder="VIP Fee"  
                   value="<?= isset($landing_page['LANDING_PAGE_VIP_FEE']) ? htmlspecialchars($landing_page['LANDING_PAGE_VIP_FEE']) : '' ?>">

                               
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4" for="TESTIMONIALS_IMAGES">Testimonial<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <input 
                                    class="form-control" 
                                    name="TESTIMONIALS_IMAGES[]" 
                                    id="TESTIMONIALS_IMAGES" 
                                    type="file" 
                                    accept="image/*" 
                                    multiple 
                                />
                                <a href="<?= $landing_page['TESTIMONIALS_IMAGES'] ?>" target="_blank">View Current Images</a>
                                <div id="preview"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-form-label col-md-4">Email Template<span style="color: red;">*</span></label>
                            <div class="col-md-8">
                                <select name="EMAIL_TEMPLATE" id="EMAIL_TEMPLATE" class="form-control" onchange="">
                                    <option value="-1" <?= (isset($landing_page['EMAIL_TEMPLATE']) && $landing_page['EMAIL_TEMPLATE'] == -1) ? 'selected' : '' ?>>Select</option>
                                    <?php foreach ($all_templates as $template) : ?>
                                        <option value="<?php echo $template['TEMPLATE_ID']; ?>" <?= (isset($landing_page['EMAIL_TEMPLATE']) && $landing_page['EMAIL_TEMPLATE'] == $template['TEMPLATE_ID']) ? 'selected' : '' ?>><?php echo $template['TEMPLATE_NAME']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="form-group row">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">Update Landing Page</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Default Basic Forms End -->
    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>

</html>