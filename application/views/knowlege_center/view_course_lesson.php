<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title">
                        <h4>View Lesson Details</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('/knowledge-center'); ?>" role="button">
                        Back To Course List
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

            <div class="row">

            <div class="col-md-12">
                    <div class="form-group row">
                        <!-- <label class="col-form-label col -md-4">CONTENT <span style="color: red;">*</span></label> -->
                        <div class="col-md-8">
                            <p class="form-control-static"> <img src="<?= $lesson_data['THUMBNAIL_IMAGE'] ?> "></p>
                        </div>
                    </div>
                </div>
            </div>
           
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Name <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lesson_data['TITLE'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Objective <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lesson_data['OBJECTIVE'] ?></p>
                        </div>
                    </div>
                </div>
            

            <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">CONTENT <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static"><?= $lesson_data['CONTENT'] ?></p>
                        </div>
                    </div>
                </div>
          

        

         

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-form-label col-md-4">Attachment <span style="color: red;">*</span></label>
                        <div class="col-md-8">
                            <p class="form-control-static">
                                <?php if ($lesson_data['ATTACHMENT']): ?>
                                    <iframe 
                                        src="<?= $lesson_data['ATTACHMENT'] ?>" 
                                        style="width: 100%; height: 500px; border: 1px solid #ddd;" 
                                        frameborder="0">
                                    </iframe>
                                <?php else: ?>
                                    NA
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
    <div class="col-md-12">
        <div class="form-group row">
            <label class="col-form-label col-md-4">Video Link <span style="color: red;">*</span></label>
            <div class="col-md-8">
                <p class="form-control-static">
                    <?php if ($lesson_data['LESSON_VIDEO_LINK']): ?>
                        <?php
                        $videoLink = $lesson_data['LESSON_VIDEO_LINK'];
                        
                        // Check if it's a YouTube link
                        if (strpos($videoLink, 'youtube.com') !== false || strpos($videoLink, 'youtu.be') !== false) {
                            // Convert YouTube link to embeddable format
                            if (strpos($videoLink, 'watch?v=') !== false) {
                                $videoLink = str_replace('watch?v=', 'embed/', $videoLink);
                            } elseif (strpos($videoLink, 'youtu.be') !== false) {
                                $videoLink = str_replace('youtu.be/', 'www.youtube.com/embed/', $videoLink);
                            }
                        ?>
                            <!-- Embed YouTube video -->
                            <iframe 
                                width="100%" 
                                height="315" 
                                src="<?= $videoLink ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen>
                            </iframe>
                        <?php } else { ?>
                            <!-- Embed self-hosted video -->
                            <video 
                                width="100%" 
                                height="315" 
                                controls>
                                <source src="<?= $videoLink ?>" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        <?php } ?>
                    <?php else: ?>
                        NA
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>

            
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>

</html>