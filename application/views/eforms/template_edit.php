<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>

<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title"><h4>Edit Template</h4></div>
                </div>
                <div class="col-md-6 col-sm-12 text-right">
                    <a class="btn btn-warning" href="<?= base_url('admin_eforms/templates'); ?>" role="button">Templates List</a>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')); ?></div>
            <?php endif; ?>
            <form method="post" action="<?= base_url('admin_eforms/template_update/' . $template['id']); ?>" onsubmit="return syncCkEditorsBeforeSubmit();">

                <!-- BANNER IMAGE URL -->
                <?php $banner_url = $overrides['banner_image_url'] ?? ''; ?>
                <div class="form-group row">
                    <label class="col-form-label col-md-2">
                        Banner Image URL
                        <small class="d-block text-muted" style="font-weight:400;font-size:11px;">Shown below the EYD header bar</small>
                    </label>
                    <div class="col-md-10">
                        <input class="form-control" name="banner_image_url"
                               value="<?= html_escape($banner_url); ?>"
                               placeholder="https://example.com/banner.jpg">
                        <?php if (!empty($banner_url)): ?>
                        <div id="banner-preview" style="margin-top:10px;">
                            <img src="<?= html_escape($banner_url); ?>" alt="Banner Preview"
                                 style="max-width:100%;max-height:160px;border-radius:8px;border:1px solid #ddd;object-fit:cover;">
                        </div>
                        <?php else: ?>
                        <div id="banner-preview" style="margin-top:10px;display:none;">
                            <img src="" alt="Banner Preview"
                                 style="max-width:100%;max-height:160px;border-radius:8px;border:1px solid #ddd;object-fit:cover;">
                        </div>
                        <?php endif; ?>
                        <small class="text-muted">Paste a direct image URL. Leave blank for no banner.</small>
                    </div>
                </div>

                <hr>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Slug <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" name="slug" required value="<?= html_escape($template['slug']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Title <span style="color:red;">*</span></label>
                    <div class="col-md-10">
                        <input class="form-control" name="title" required value="<?= html_escape($template['title']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Heading</label>
                    <div class="col-md-10">
                        <input class="form-control" name="heading" value="<?= html_escape($template['heading']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Subheading</label>
                    <div class="col-md-10">
                        <input class="form-control" name="subheading" value="<?= html_escape($template['subheading']); ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Body HTML</label>
                    <div class="col-md-10">
                        <textarea class="form-control ckeditor" name="body_html" rows="8"><?= html_escape($template['body_html']); ?></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-form-label col-md-2">Agree Checkbox Text</label>
                    <div class="col-md-10">
                        <?php $agree = $overrides['texts']['agree_checkbox_text'] ?? 'I have read and agree.'; ?>
                        <input class="form-control" name="agree_checkbox_text" value="<?= html_escape($agree); ?>">
                    </div>
                </div>

                <?php
                    // Default ON for existing templates that never set this flag
                    $enable_signature = !isset($overrides['enable_signature'])
                        || (string)$overrides['enable_signature'] === '1'
                        || $overrides['enable_signature'] === 1
                        || $overrides['enable_signature'] === true;
                ?>
                <div class="form-group row">
                    <label class="col-form-label col-md-2">Signature Box</label>
                    <div class="col-md-10">
                        <div class="custom-control custom-switch" style="padding-top:8px;">
                            <input type="checkbox" class="custom-control-input" id="enable_signature"
                                   name="enable_signature" value="1" <?= $enable_signature ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="enable_signature">
                                Does this form have a signature box?
                            </label>
                        </div>
                        <small class="text-muted">When off, the public fill form hides the signature pad and clients can submit without signing.</small>
                    </div>
                </div>

                <hr>

                <!-- ══════════════════════════════════════════
                     SECTIONS
                     Stored in overrides_json['sections'] as
                     [ {id:'sec_0', name:'...'}, ... ]
                ═══════════════════════════════════════════ -->
                <div class="clearfix mb-2">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Sections</h4>
                        <p class="text-muted" style="margin:0;">
                            Create named sections here, then assign each field to a section below.<br>
                            Fields with no section assigned will appear without a section header.
                        </p>
                    </div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-info btn-sm" onclick="addSection()">+ Add Section</button>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-sm" id="sections_tbl">
                        <thead>
                            <tr>
                                <th style="width:10%;">ID <small class="text-muted">(auto)</small></th>
                                <th>Section Name <span style="color:red;">*</span></th>
                                <th style="width:12%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="sections_body">
                            <?php
                            $existing_sections = $overrides['sections'] ?? [];
                            foreach ($existing_sections as $sec):
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" name="section_id[]" value="<?= html_escape($sec['id']); ?>">
                                    <code style="font-size:12px;"><?= html_escape($sec['id']); ?></code>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm section-name-input"
                                           name="section_name[]"
                                           value="<?= html_escape($sec['name']); ?>"
                                           placeholder="e.g. Personal Details"
                                           required
                                           data-id="<?= html_escape($sec['id']); ?>"
                                           oninput="syncSectionOption(this)">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm"
                                            onclick="removeSection(this, '<?= html_escape($sec['id']); ?>')">Remove</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="text-muted" id="no_sections_msg" style="<?= !empty($existing_sections) ? 'display:none;' : '' ?>font-size:13px;">
                        No sections yet. Click <strong>+ Add Section</strong> to create one.
                    </p>
                </div>

                <hr>

                <!-- ══════════════════════════════════════════
                     FIELD LABEL OVERRIDES + SECTION ASSIGNMENT
                ═══════════════════════════════════════════ -->
                <div class="clearfix mb-2">
                    <div class="pull-left">
                        <h4 class="text-blue h4">Field Label Overrides &amp; Section Assignment</h4>
                        <p class="text-muted" style="margin:0;">Override labels, assign sections, and optionally add HTML content blocks before or after any field.</p>
                    </div>
                </div>

                <style>
                    .field-html-actions { display:flex; flex-wrap:wrap; gap:6px; align-items:center; }
                    .field-html-badge {
                        display:inline-block;
                        font-size:11px;
                        font-weight:600;
                        padding:2px 7px;
                        border-radius:999px;
                        margin-top:4px;
                        background:#e8f4fd;
                        color:#0b5394;
                    }
                    .field-html-panel-row { display:none; }
                    .field-html-panel-row.is-open { display:table-row; }
                    .field-html-summary-row { display:none; }
                    .field-html-summary-row.is-visible { display:table-row; }
                    .field-html-summary {
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                        gap:10px;
                        padding:8px 12px;
                        border:1px dashed #9ec5e8;
                        border-radius:8px;
                        background:#f3f9ff;
                        font-size:12px;
                        color:#1b2a4e;
                    }
                    .field-html-summary-preview {
                        flex:1;
                        min-width:0;
                        color:#5f6368;
                        white-space:nowrap;
                        overflow:hidden;
                        text-overflow:ellipsis;
                    }
                    .field-html-panel {
                        border:1px solid #dbe2ea;
                        border-radius:8px;
                        background:#fff;
                        padding:12px 14px;
                    }
                    .field-html-panel-head {
                        display:flex;
                        align-items:center;
                        justify-content:space-between;
                        gap:10px;
                        margin-bottom:10px;
                    }
                    .field-html-panel-head strong { font-size:13px; color:#1b2a4e; }
                    .field-html-panel-actions { display:flex; gap:6px; flex-wrap:wrap; }
                </style>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:16%;">Field Name</th>
                                <th style="width:18%;">Default Label</th>
                                <th style="width:24%;">Override Label</th>
                                <th style="width:22%;">Section</th>
                                <th style="width:20%;">HTML Blocks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!is_array($overrides)) {
                                $overrides = [];
                            }
                            $field_sections = $overrides['field_sections'] ?? [];
                            $field_html_before = is_array($overrides['field_html_before'] ?? null)
                                ? $overrides['field_html_before']
                                : [];
                            $field_html_after = is_array($overrides['field_html_after'] ?? null)
                                ? $overrides['field_html_after']
                                : [];
                            foreach ($fields as $f):
                                $fname          = $f['name'];
                                $field_uid      = 'f_' . substr(md5($fname), 0, 10);
                                $ftype          = $f['type'] ?? 'text';
                                $default_label  = $f['label'];
                                $override_label = $overrides['labels'][$fname] ?? $default_label;
                                $assigned_sec   = $field_sections[$fname] ?? '';
                                $html_before    = (string)($field_html_before[$fname] ?? '');
                                $html_after     = (string)($field_html_after[$fname] ?? '');
                                $has_before     = trim(strip_tags($html_before)) !== '' || preg_match('/<(img|iframe|video|table|hr|svg)\b/i', $html_before);
                                $has_after      = trim(strip_tags($html_after)) !== '' || preg_match('/<(img|iframe|video|table|hr|svg)\b/i', $html_after);
                                $preview_before = trim(preg_replace('/\s+/', ' ', strip_tags($html_before)));
                                $preview_after  = trim(preg_replace('/\s+/', ' ', strip_tags($html_after)));
                                if (strlen($preview_before) > 90) {
                                    $preview_before = substr($preview_before, 0, 90) . '…';
                                }
                                if (strlen($preview_after) > 90) {
                                    $preview_after = substr($preview_after, 0, 90) . '…';
                                }

                                // Skip display-only fields from section assignment
                                $is_display = in_array($ftype, ['image','video','section']);
                            ?>
                            <tr class="field-html-panel-row<?= $has_before ? ' is-open has-content' : ''; ?>"
                                id="html-before-row-<?= html_escape($field_uid); ?>"
                                data-field-uid="<?= html_escape($field_uid); ?>"
                                data-position="before"
                                data-has-content="<?= $has_before ? '1' : '0'; ?>">
                                <td colspan="5" style="background:#f8fbff;border-bottom:none;">
                                    <div class="field-html-panel">
                                        <div class="field-html-panel-head">
                                            <strong>HTML Before — <?= html_escape($fname); ?></strong>
                                            <div class="field-html-panel-actions">
                                                <button type="button" class="btn btn-light btn-sm"
                                                        onclick="collapseFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'before')">Hide</button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'before')">Remove</button>
                                            </div>
                                        </div>
                                        <textarea id="field_html_before_<?= html_escape($field_uid); ?>"
                                                  class="ckeditor-field-block-lazy"
                                                  name="field_html_before[<?= html_escape($fname); ?>]"
                                                  rows="4"
                                                  data-initial-html="<?= html_escape($html_before); ?>"
                                                  placeholder="HTML shown before this field on the public form."><?= $html_before; ?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr class="field-html-summary-row"
                                id="html-before-summary-<?= html_escape($field_uid); ?>"
                                data-field-uid="<?= html_escape($field_uid); ?>"
                                data-position="before">
                                <td colspan="5" style="background:#f8fbff;border-bottom:none;">
                                    <div class="field-html-summary">
                                        <div>
                                            <strong>HTML block before this field</strong>
                                            <?php if ($preview_before !== ''): ?>
                                                <div class="field-html-summary-preview"><?= html_escape($preview_before); ?></div>
                                            <?php else: ?>
                                                <div class="field-html-summary-preview text-muted">Saved HTML content</div>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="openFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'before')">Edit</button>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input class="form-control form-control-sm" name="field_name[]"
                                           value="<?= html_escape($fname); ?>" readonly>
                                    <?php if ($is_display): ?>
                                        <small class="text-muted">(<?= html_escape($ftype) ?>)</small>
                                    <?php endif; ?>
                                </td>
                                <td style="vertical-align:middle;"><?= html_escape($default_label); ?></td>
                                <td>
                                    <input class="form-control form-control-sm" name="field_label_override[]"
                                           value="<?= html_escape($override_label); ?>"
                                           <?= $is_display ? 'readonly style="background:#f5f5f5;"' : '' ?>>
                                </td>
                                <td>
                                    <?php if (!$is_display): ?>
                                    <select class="form-control form-control-sm field-section-select"
                                            name="field_section[]"
                                            data-field="<?= html_escape($fname); ?>">
                                        <option value="">— No Section —</option>
                                        <?php foreach ($existing_sections as $sec): ?>
                                        <option value="<?= html_escape($sec['id']); ?>"
                                                <?= $assigned_sec === $sec['id'] ? 'selected' : '' ?>>
                                            <?= html_escape($sec['name']); ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size:12px;">N/A (display field)</span>
                                        <input type="hidden" name="field_section[]" value="">
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="field-html-actions">
                                        <button type="button"
                                                class="btn btn-outline-info btn-sm btn-html-before"
                                                id="btn-html-before-<?= html_escape($field_uid); ?>"
                                                data-field-uid="<?= html_escape($field_uid); ?>"
                                                data-has-content="<?= $has_before ? '1' : '0'; ?>"
                                                onclick="openFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'before')">
                                            <?= $has_before ? 'Edit block before' : '+ Add block before' ?>
                                        </button>
                                        <button type="button"
                                                class="btn btn-outline-info btn-sm btn-html-after"
                                                id="btn-html-after-<?= html_escape($field_uid); ?>"
                                                data-field-uid="<?= html_escape($field_uid); ?>"
                                                data-has-content="<?= $has_after ? '1' : '0'; ?>"
                                                onclick="openFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'after')">
                                            <?= $has_after ? 'Edit block after' : '+ Add block after' ?>
                                        </button>
                                    </div>
                                    <?php if ($has_before): ?>
                                        <span class="field-html-badge">Block before</span>
                                    <?php endif; ?>
                                    <?php if ($has_after): ?>
                                        <span class="field-html-badge">Block after</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <tr class="field-html-panel-row<?= $has_after ? ' is-open has-content' : ''; ?>"
                                id="html-after-row-<?= html_escape($field_uid); ?>"
                                data-field-uid="<?= html_escape($field_uid); ?>"
                                data-position="after"
                                data-has-content="<?= $has_after ? '1' : '0'; ?>">
                                <td colspan="5" style="background:#f8fbff;">
                                    <div class="field-html-panel">
                                        <div class="field-html-panel-head">
                                            <strong>HTML After — <?= html_escape($fname); ?></strong>
                                            <div class="field-html-panel-actions">
                                                <button type="button" class="btn btn-light btn-sm"
                                                        onclick="collapseFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'after')">Hide</button>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                        onclick="removeFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'after')">Remove</button>
                                            </div>
                                        </div>
                                        <textarea id="field_html_after_<?= html_escape($field_uid); ?>"
                                                  class="ckeditor-field-block-lazy"
                                                  name="field_html_after[<?= html_escape($fname); ?>]"
                                                  rows="4"
                                                  data-initial-html="<?= html_escape($html_after); ?>"
                                                  placeholder="HTML shown after this field on the public form."><?= $html_after; ?></textarea>
                                    </div>
                                </td>
                            </tr>
                            <tr class="field-html-summary-row"
                                id="html-after-summary-<?= html_escape($field_uid); ?>"
                                data-field-uid="<?= html_escape($field_uid); ?>"
                                data-position="after">
                                <td colspan="5" style="background:#f8fbff;">
                                    <div class="field-html-summary">
                                        <div>
                                            <strong>HTML block after this field</strong>
                                            <?php if ($preview_after !== ''): ?>
                                                <div class="field-html-summary-preview"><?= html_escape($preview_after); ?></div>
                                            <?php else: ?>
                                                <div class="field-html-summary-preview text-muted">Saved HTML content</div>
                                            <?php endif; ?>
                                        </div>
                                        <button type="button" class="btn btn-outline-info btn-sm"
                                                onclick="openFieldHtmlPanel('<?= html_escape($field_uid); ?>', 'after')">Edit</button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="form-group row mt-3">
                    <div class="col-md-6 offset-md-2">
                        <button type="submit" class="btn btn-warning">
                            Update Template
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Live banner preview
    const bannerInput = document.querySelector('input[name="banner_image_url"]');
    const previewDiv  = document.getElementById('banner-preview');
    const previewImg  = previewDiv ? previewDiv.querySelector('img') : null;
    if (bannerInput && previewDiv && previewImg) {
        bannerInput.addEventListener('input', function () {
            const val = this.value.trim();
            if (val) { previewImg.src = val; previewDiv.style.display = 'block'; }
            else     { previewDiv.style.display = 'none'; previewImg.src = ''; }
        });
    }

    // CKEditor — body HTML only (field blocks init on demand)
    CKEDITOR.replaceAll('ckeditor', {
        height: 320,
        extraPlugins: 'justify,colorbutton,font,showblocks,codesnippet',
        removePlugins: 'elementspath',
        toolbar: [
            { name: 'document',    items: ['Source', '-', 'Preview', '-', 'Maximize'] },
            { name: 'clipboard',   items: ['Undo', 'Redo'] },
            { name: 'styles',      items: ['Format', 'Font', 'FontSize'] },
            { name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','RemoveFormat'] },
            { name: 'paragraph',   items: ['NumberedList','BulletedList','-','Outdent','Indent','-',
                                           'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
            { name: 'links',       items: ['Link','Unlink'] },
            { name: 'insert',      items: ['Table','HorizontalRule'] },
            { name: 'colors',      items: ['TextColor','BGColor'] }
        ]
    });

    // Init editors for blocks that already have saved content
    window.setTimeout(function() {
        document.querySelectorAll('.field-html-panel-row.is-open textarea.ckeditor-field-block-lazy').forEach(function(ta) {
            initFieldCkEditor(ta.id);
        });
    }, 50);
});

var ckFieldEditors = {};

function fieldHtmlTextareaId(fieldUid, position) {
    return 'field_html_' + position + '_' + fieldUid;
}

function fieldHtmlSummaryId(fieldUid, position) {
    return 'html-' + position + '-summary-' + fieldUid;
}

function initFieldCkEditor(textareaId) {
    if (!window.CKEDITOR || !textareaId || ckFieldEditors[textareaId] || CKEDITOR.instances[textareaId]) {
        return;
    }
    var el = document.getElementById(textareaId);
    if (!el) return;

    var initialData = el.value || el.getAttribute('data-initial-html') || '';
    if (!el.value && initialData) {
        el.value = initialData;
    }

    ckFieldEditors[textareaId] = CKEDITOR.replace(textareaId, {
        height: 180,
        removePlugins: 'elementspath',
        toolbar: [
            { name: 'document', items: ['Source'] },
            { name: 'basicstyles', items: ['Bold','Italic','Underline','RemoveFormat'] },
            { name: 'paragraph', items: ['NumberedList','BulletedList','-','Link','Unlink'] }
        ]
    });

    ckFieldEditors[textareaId].on('instanceReady', function(ev) {
        var current = ev.editor.getData();
        if ((!current || current.replace(/\s|&nbsp;|<br\s*\/?>/gi, '') === '') && initialData) {
            ev.editor.setData(initialData);
        }
    });
}

function updateHtmlBlockButton(fieldUid, position, hasContent) {
    var btn = document.getElementById('btn-html-' + position + '-' + fieldUid);
    if (!btn) return;
    btn.dataset.hasContent = hasContent ? '1' : '0';
    btn.textContent = hasContent
        ? ('Edit block ' + position)
        : ('+ Add block ' + position);
}

function openFieldHtmlPanel(fieldUid, position) {
    var row = document.getElementById('html-' + position + '-row-' + fieldUid);
    var summary = document.getElementById(fieldHtmlSummaryId(fieldUid, position));
    if (!row) return;
    row.classList.add('is-open');
    if (summary) summary.classList.remove('is-visible');
    initFieldCkEditor(fieldHtmlTextareaId(fieldUid, position));
    row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function collapseFieldHtmlPanel(fieldUid, position) {
    var row = document.getElementById('html-' + position + '-row-' + fieldUid);
    var summary = document.getElementById(fieldHtmlSummaryId(fieldUid, position));
    if (row) row.classList.remove('is-open');
    if (summary && row && row.getAttribute('data-has-content') === '1') {
        summary.classList.add('is-visible');
    }
}

function removeFieldHtmlPanel(fieldUid, position) {
    if (!confirm('Remove this HTML block? It will not appear on the public form after you save.')) {
        return;
    }
    var textareaId = fieldHtmlTextareaId(fieldUid, position);
    var ta = document.getElementById(textareaId);
    if (ta) {
        ta.value = '';
        ta.setAttribute('data-initial-html', '');
    }
    if (window.CKEDITOR && CKEDITOR.instances[textareaId]) {
        CKEDITOR.instances[textareaId].destroy(true);
        delete ckFieldEditors[textareaId];
    }
    var row = document.getElementById('html-' + position + '-row-' + fieldUid);
    if (row) {
        row.classList.remove('is-open');
        row.classList.remove('has-content');
        row.setAttribute('data-has-content', '0');
    }
    var summary = document.getElementById(fieldHtmlSummaryId(fieldUid, position));
    if (summary) summary.classList.remove('is-visible');
    collapseFieldHtmlPanel(fieldUid, position);
    updateHtmlBlockButton(fieldUid, position, false);
}

function syncCkEditorsBeforeSubmit() {
    if (window.CKEDITOR && CKEDITOR.instances) {
        for (var instance in CKEDITOR.instances) {
            if (Object.prototype.hasOwnProperty.call(CKEDITOR.instances, instance)) {
                CKEDITOR.instances[instance].updateElement();
            }
        }
    }
    return confirm('Are you sure you want to update this template?');
}

// ── Section counter (start after existing sections) ──
let sectionCounter = <?= count($existing_sections) ?>;

function addSection() {
    const id   = 'sec_' + sectionCounter++;
    const body = document.getElementById('sections_body');
    const tr   = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <input type="hidden" name="section_id[]" value="${id}">
            <code style="font-size:12px;">${id}</code>
        </td>
        <td>
            <input class="form-control form-control-sm section-name-input"
                   name="section_name[]"
                   placeholder="e.g. Business Details"
                   required
                   data-id="${id}"
                   oninput="syncSectionOption(this)">
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeSection(this, '${id}')">Remove</button>
        </td>
    `;
    body.appendChild(tr);

    // Add option to every field section select
    document.querySelectorAll('.field-section-select').forEach(sel => {
        const opt = document.createElement('option');
        opt.value       = id;
        opt.textContent = '(new section)';
        opt.dataset.id  = id;
        sel.appendChild(opt);
    });

    document.getElementById('no_sections_msg').style.display = 'none';
}

function removeSection(btn, secId) {
    if (!confirm('Remove this section? Fields assigned to it will become unassigned.')) return;

    // Remove the row
    btn.closest('tr').remove();

    // Remove this option from all field-section selects and reset if selected
    document.querySelectorAll('.field-section-select').forEach(sel => {
        if (sel.value === secId) sel.value = '';
        const opt = sel.querySelector(`option[value="${secId}"]`);
        if (opt) opt.remove();
    });

    const body = document.getElementById('sections_body');
    if (body.children.length === 0) {
        document.getElementById('no_sections_msg').style.display = 'block';
    }
}

function syncSectionOption(input) {
    const id   = input.dataset.id;
    const name = input.value || '(unnamed)';
    document.querySelectorAll(`.field-section-select option[value="${id}"]`).forEach(opt => {
        opt.textContent = name;
    });
}
</script>

</body>
<?php $this->load->view('includes/footer'); ?>
</html>