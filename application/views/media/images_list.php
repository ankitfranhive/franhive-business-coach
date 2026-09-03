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
                        <h4>Media Library - Images</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= html_escape($success) ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= html_escape($error) ?></div>
            <?php endif; ?>

            <!-- Upload Form -->
            <div class="clearfix" style="margin-bottom:12px;">
                <div class="pull-left">
                    <h5 style="margin:0;">Upload New Image</h5>
                    <small class="text-muted">Uploads to: <b>uploads/<?= date('Y') ?>/<?= date('m') ?>/</b></small>
                </div>
            </div>

            <form method="post" action="<?= base_url('admin_media/upload_image'); ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control" type="file" name="image_file" accept=".jpg,.jpeg,.png,.gif,.webp" required>
                        <small class="text-muted">Allowed: jpg, jpeg, png, gif, webp (max 5MB)</small>
                    </div>
                    <div class="col-md-3" style="display:flex; align-items:end;">
                        <button type="submit" class="btn btn-warning">Upload</button>
                    </div>
                </div>
            </form>

            <hr>

            <!-- Filters -->
            <form method="get" action="<?= base_url('admin_media/images'); ?>">
                <div class="row">
                    <div class="col-md-4">
                        <label><b>Search filename</b></label>
                        <input class="form-control" type="text" name="q" value="<?= html_escape($q ?? '') ?>" placeholder="e.g. banner, logo, 24.png">
                    </div>
                    <div class="col-md-2">
                        <label><b>Year</b></label>
                        <input class="form-control" type="text" name="year" value="<?= html_escape($year ?? '') ?>" placeholder="2025">
                    </div>
                    <div class="col-md-2">
                        <label><b>Month</b></label>
                        <input class="form-control" type="text" name="month" value="<?= html_escape($month ?? '') ?>" placeholder="12">
                    </div>
                    <div class="col-md-4" style="display:flex; align-items:end; gap:10px;">
                        <button type="submit" class="btn btn-warning">Filter</button>
                        <a class="btn btn-light" href="<?= base_url('admin_media/images'); ?>">Reset</a>
                    </div>
                </div>
            </form>

            <hr>

            <div class="clearfix" style="margin-bottom:10px;">
                <div class="pull-left">
                    <b>Total Images:</b> <?= (int)($total ?? 0) ?>
                </div>
            </div>

            <style>
                .img-card { border:1px solid #eee; border-radius:10px; overflow:hidden; background:#fff; }
                .img-thumb { width:100%; height:140px; object-fit:cover; background:#f6f7fb; display:block; }
                .img-meta { padding:10px; }
                .img-meta .name { font-weight:700; font-size:13px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
                .img-meta .path { font-size:12px; color:#666; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
                .img-meta .btns { margin-top:8px; display:flex; gap:8px; flex-wrap:wrap; }
                .codebox { font-family: monospace; font-size:12px; word-break:break-all; background:#f8f9fa; border:1px solid #eee; padding:8px; border-radius:8px; }
            </style>

            <!-- Image Grid -->
            <div class="row">
                <?php if(!empty($images)): ?>
                    <?php foreach($images as $img): ?>
                        <div class="col-lg-3 col-md-4 col-sm-6" style="margin-bottom:15px;">
                            <div class="img-card">
                                <img class="img-thumb" loading="lazy" src="<?= html_escape($img['url']) ?>" alt="<?= html_escape($img['file']) ?>">
                                <div class="img-meta">
                                    <div class="name" title="<?= html_escape($img['file']) ?>"><?= html_escape($img['file']) ?></div>
                                    <div class="path" title="<?= html_escape($img['rel']) ?>"><?= html_escape($img['rel']) ?></div>

                                    <div class="btns">
                                        <button type="button" class="btn btn-sm btn-warning"
                                                onclick="openPreview('<?= html_escape($img['url']) ?>')">
                                            Preview
                                        </button>

                                        <button type="button" class="btn btn-sm btn-light"
                                                onclick="copyUrl('<?= html_escape($img['url']) ?>')">
                                            Copy URL
                                        </button>

                                        <form method="post" action="<?= base_url('admin_media/delete_image'); ?>" style="display:inline;">
                                            <input type="hidden" name="rel_path" value="<?= html_escape($img['rel']) ?>">
                                            <input type="hidden" name="back_url" value="<?= html_escape(current_url().'?'.http_build_query($_GET)) ?>">
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('⚠️ Permanently delete this image?\\n\\nThis cannot be undone and may break pages where this image is used.\\n\\nClick OK to delete.');">
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">No images found for this filter.</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php
                $totalPages = (int)ceil(($total ?? 0) / ($per_page ?? 48));
                $page = (int)($page ?? 1);
                $qs = $_GET;
            ?>
            <?php if ($totalPages > 1): ?>
                <hr>
                <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
                    <?php for ($p=1; $p <= $totalPages; $p++): ?>
                        <?php $qs['page'] = $p; ?>
                        <a class="btn btn-sm <?= ($p === $page) ? 'btn-warning' : 'btn-light' ?>"
                           href="<?= base_url('admin_media/images?'.http_build_query($qs)); ?>">
                           <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="imgPreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Image Preview</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img id="modalImg" src="" alt="Preview"
             style="width:100%; height:auto; border:1px solid #eee; border-radius:10px;">
        <div style="margin-top:12px;">
            <div><b>URL</b></div>
            <div class="codebox" id="modalUrlBox"></div>
            <div style="margin-top:10px; display:flex; gap:10px; flex-wrap:wrap;">
                <button type="button" class="btn btn-warning btn-sm" onclick="copyFromModal()">Copy URL</button>
                <a id="modalOpenNew" class="btn btn-light btn-sm" target="_blank" href="#">Open in new tab</a>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="closeModal()">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
let _modalUrl = '';

function openPreview(url){
    _modalUrl = url;
    document.getElementById('modalImg').src = url;
    document.getElementById('modalUrlBox').innerText = url;
    document.getElementById('modalOpenNew').href = url;

    const m = document.getElementById('imgPreviewModal');
    if (window.jQuery && jQuery.fn && jQuery.fn.modal) {
        jQuery(m).modal('show');
    } else {
        // fallback if bootstrap modal not available
        m.style.display = 'block';
        m.classList.add('show');
        m.setAttribute('aria-modal','true');
        m.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
    }
}

function closeModal(){
    const m = document.getElementById('imgPreviewModal');
    if (window.jQuery && jQuery.fn && jQuery.fn.modal) {
        jQuery(m).modal('hide');
    } else {
        m.style.display = 'none';
        m.classList.remove('show');
        m.setAttribute('aria-hidden','true');
        document.body.classList.remove('modal-open');
    }
}

async function copyUrl(url){
    try {
        await navigator.clipboard.writeText(url);
        alert('Copied: ' + url);
    } catch (e) {
        const ta = document.createElement('textarea');
        ta.value = url;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        ta.remove();
        alert('Copied: ' + url);
    }
}

function copyFromModal(){
    if (_modalUrl) copyUrl(_modalUrl);
}
</script>
<script>
  setTimeout(function(){
    document.querySelectorAll('.alert').forEach(a => a.style.display = 'none');
  }, 3000);
</script>


</body>
<?php $this->load->view('includes/footer'); ?>
</html>
