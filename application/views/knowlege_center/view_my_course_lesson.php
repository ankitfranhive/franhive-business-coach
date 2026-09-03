<!DOCTYPE html>
<html lang="en">
<?php $this->load->view('includes/header'); ?>
<style>
   /* Make modal truly fullscreen even on older Bootstrap versions */
.modal-fullscreen {
  width: 100vw;
  height: 100vh;
  margin: 0;
  padding: 0;
  max-width: 100%;
}

.modal-content {
  height: 100vh;
  border-radius: 0;
}

.modal-body iframe {
  height: calc(100vh - 60px); /* Adjust for header height */
}

</style>
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-6 col-sm-12">
                    <h4 class="text-primary font-weight-bold">Lesson Details</h4>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning btn-sm" 
                        href="<?= $lesson_data['COURSE_ID'] != null 
                            ? base_url('my-lessons/' . $lesson_data['COURSE_ID']) 
                            : base_url('my-courses') ?>" 
                        role="button">
                        Back to My Course Lessons
                    </a>
                </div>
            </div>
        </div>

        <!-- Lesson Details Card -->
        <div class="card shadow-sm p-4 mb-4">
            <div class="card-body">
                
                <!-- Cover Image on Top Right -->
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="text-dark font-weight-bold">Basic Information</h5>
                        <hr>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <strong>Name:</strong>
                                <p class="text-muted"><?= $lesson_data['TITLE'] ?></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Objective:</strong>
                                <p class="text-muted"><?= $lesson_data['OBJECTIVE'] ?></p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Content:</strong>
                                <p class="text-muted"><?= $lesson_data['CONTENT'] ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Cover Image Positioned to Right -->
                    <div class="col-md-4 text-center">
                        <img src="<?= $lesson_data['THUMBNAIL_IMAGE'] ?>" 
                             class="img-fluid rounded shadow-sm" 
                             style="max-width: 100%; height: auto;" 
                             alt="Lesson Thumbnail">
                    </div>
                </div>

                <!-- Attachments Section -->
                <h5 class="text-dark font-weight-bold mt-4">Attachments</h5>
                <hr>
                <div class="bg-light p-3 rounded">
                    <?php 
                    if (!empty($lesson_data['ATTACHMENT'])) {
                        $attachments = json_decode($lesson_data['ATTACHMENT'], true);
                        if (!empty($attachments) && is_array($attachments)) {
                            echo '<div class="d-flex flex-wrap">';
                            foreach ($attachments as $index => $file) {
                                echo '<div class="p-2">
                                      <a href="#" 
                                class="btn btn-sm btn-outline-primary view-pdf" 
                                data-file="' . $file . '">
                                <i class="fa fa-paperclip"></i> Attachment ' . ($index + 1) . '
                                </a>

                            </div>';
                            }
                            echo '</div>';
                        } else {
                            echo "<p class='text-muted'>NA</p>";
                        }
                    } else {
                        echo "<p class='text-muted'>NA</p>";
                    }
                    ?>
                </div>

                <!-- Video Link -->
                <h5 class="text-dark font-weight-bold mt-4">Lesson Video</h5>
                <hr>
                <div>
                    <?= ($lesson_data['LESSON_VIDEO_LINK']) 
                        ? '<a href="' . $lesson_data['LESSON_VIDEO_LINK'] . '" 
                               target="_blank" 
                               class="btn btn-primary btn-sm">
                               <i class="fa fa-play"></i> Click to Play
                           </a>' 
                        : "<p class='text-muted'>NA</p>"; 
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- PDF Viewer Modal -->
<!-- Fullscreen PDF Viewer Modal -->
<div class="modal fade" id="pdfModal" tabindex="-1" role="dialog" aria-labelledby="pdfModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen" role="document">
    <div class="modal-content border-0 rounded-0">
      <div class="modal-header bg-dark text-white d-flex justify-content-between align-items-center">
        <h5 class="modal-title mb-0">Attachment Viewer</h5>
        <button type="button" class="btn btn-sm btn-light" data-dismiss="modal" aria-label="Close">
          <i class="fa fa-times"></i> Close
        </button>
        <a id="downloadPdf" class="btn btn-sm btn-primary" target="_blank">
  <i class="fa fa-download"></i> Download
</a>
      </div>
      <div class="modal-body p-0" style="height: 100vh;">
        <iframe id="pdfFrame" src="" width="100%" height="100%" style="border: none;"></iframe>
      </div>
    </div>
  </div>
</div>


<?php $this->load->view('includes/footer'); ?>
</html>
<script>
$(document).ready(function() {
    $('.view-pdf').click(function(e) {
        e.preventDefault();
        var fileUrl = $(this).data('file');
        $('#pdfFrame').attr('src', fileUrl);
        $('#pdfModal').modal('show');
        $('#downloadPdf').attr('href', fileUrl);
    });

    $('#pdfModal').on('hidden.bs.modal', function () {
        $('#pdfFrame').attr('src', ''); // Clear iframe on close
    });
});
</script>


