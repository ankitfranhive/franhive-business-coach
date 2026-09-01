<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<style>
    .submissions-filter-card {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 18px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .submissions-filter-card .filter-title {
        font-size: 15px;
        font-weight: 700;
        color: #1b2a4e;
        margin-bottom: 4px;
    }
    .submissions-filter-card .filter-help {
        font-size: 12.5px;
        color: #6b7280;
        margin-bottom: 12px;
    }
    .template-check-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 8px 12px;
        max-height: 220px;
        overflow-y: auto;
        padding: 12px;
        border: 1px solid #e5e9f2;
        border-radius: 10px;
        background: #f8fafc;
        margin-bottom: 14px;
    }
    .template-check-item {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin: 0;
        padding: 8px 10px;
        border-radius: 8px;
        background: #fff;
        border: 1px solid #e8edf5;
        cursor: pointer;
        font-size: 13px;
        color: #111827;
        line-height: 1.35;
    }
    .template-check-item:hover {
        border-color: #c7d2fe;
        background: #f5f7ff;
    }
    .template-check-item input {
        margin-top: 2px;
        flex: 0 0 auto;
    }
    .template-check-item.is-checked {
        border-color: #f0c14b;
        background: #fffbeb;
    }
    .filter-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }
    .filter-meta {
        margin-left: auto;
        font-size: 13px;
        color: #6b7280;
    }
    .selected-chips {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 12px;
    }
    .selected-chip {
        display: inline-flex;
        align-items: center;
        background: #fff8e1;
        border: 1px solid #ffe082;
        color: #7a5800;
        border-radius: 999px;
        padding: 3px 10px;
        font-size: 12px;
        font-weight: 600;
    }
    .results-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        gap: 10px;
        flex-wrap: wrap;
    }
    .results-bar strong {
        color: #1b2a4e;
    }
</style>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">

        <div class="page-header">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="title"><h4>Submitted Forms</h4></div>
                </div>
            </div>
        </div>

        <div class="pd-20 card-box mb-30">
            <form method="get" action="<?= base_url('admin_eforms/submissions'); ?>" class="submissions-filter-card">
                <div class="filter-title">Filter by Template</div>
                <div class="filter-help">Select one or more templates, then click Apply Filter. Leave all unchecked to show everything.</div>

                <?php if (!empty($selected_template_names)): ?>
                    <div class="selected-chips">
                        <?php foreach ($selected_template_names as $name): ?>
                            <span class="selected-chip"><?= html_escape($name); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="template-check-grid" id="templateCheckGrid">
                    <?php if (!empty($templates)): ?>
                        <?php foreach ($templates as $template): ?>
                            <?php
                                $tid = (int)($template['id'] ?? 0);
                                $is_checked = in_array($tid, $selected_template_ids ?? [], true);
                            ?>
                            <label class="template-check-item <?= $is_checked ? 'is-checked' : ''; ?>">
                                <input
                                    type="checkbox"
                                    name="template_ids[]"
                                    value="<?= $tid; ?>"
                                    <?= $is_checked ? 'checked' : ''; ?>
                                >
                                <span><?= html_escape($template['title'] ?? ('Template #' . $tid)); ?></span>
                            </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-muted">No templates found.</div>
                    <?php endif; ?>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-warning">Apply Filter</button>
                    <a href="<?= base_url('admin_eforms/submissions'); ?>" class="btn btn-outline-secondary">Clear</a>
                    <button type="button" class="btn btn-outline-primary" id="selectAllTemplates">Select All</button>
                    <button type="button" class="btn btn-outline-primary" id="clearAllTemplates">Unselect All</button>
                    <div class="filter-meta">
                        <?= (int)($total_count ?? 0); ?> result<?= ((int)($total_count ?? 0) === 1) ? '' : 's'; ?>
                        <?php if (!empty($selected_template_ids)): ?>
                            · <?= count($selected_template_ids); ?> template<?= count($selected_template_ids) === 1 ? '' : 's'; ?> selected
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <div class="results-bar">
                <div>
                    <strong>Submissions</strong>
                    <?php if (!empty($selected_template_ids)): ?>
                        <span class="text-muted">— filtered view</span>
                    <?php else: ?>
                        <span class="text-muted">— all templates</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Template</th>
                            <th>Client</th>
                            <th>Email</th>
                            <th>Submitted At</th>
                            <th>IP</th>
                            <th>PDF</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($submissions)): ?>
                        <?php foreach($submissions as $s): ?>
                            <tr>
                                <td><?= (int)$s['id'] ?></td>
                                <td><?= html_escape($s['template_title'] ?? '') ?></td>
                                <td><?= html_escape($s['client_name'] ?? '') ?></td>
                                <td><?= html_escape($s['client_email'] ?? '') ?></td>
                                <td><?= html_escape($s['created_at'] ?? '') ?></td>
                                <td><?= html_escape($s['ip_address'] ?? '') ?></td>
                                <td>
                                    <?php if(!empty($s['pdf_path'])): ?>
                                        <a class="btn btn-sm btn-success" target="_blank" href="<?= base_url('admin_eforms/download_pdf/' . (int)$s['id']); ?>">Download</a>
                                    <?php else: ?>
                                        <a class="btn btn-sm btn-outline-success" target="_blank" href="<?= base_url('admin_eforms/download_pdf/' . (int)$s['id']); ?>">Generate PDF</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="<?= base_url('admin_eforms/submission_view/'.(int)$s['id']); ?>">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8" class="text-center">No submissions found for the selected filter.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
</body>
<?php $this->load->view('includes/footer'); ?>
<script>
(function () {
  var grid = document.getElementById('templateCheckGrid');
  if (!grid) return;

  function syncCheckedClass(input) {
    var item = input.closest('.template-check-item');
    if (!item) return;
    if (input.checked) item.classList.add('is-checked');
    else item.classList.remove('is-checked');
  }

  grid.querySelectorAll('input[type="checkbox"]').forEach(function (input) {
    input.addEventListener('change', function () {
      syncCheckedClass(input);
    });
  });

  var selectAll = document.getElementById('selectAllTemplates');
  var clearAll = document.getElementById('clearAllTemplates');

  if (selectAll) {
    selectAll.addEventListener('click', function () {
      grid.querySelectorAll('input[type="checkbox"]').forEach(function (input) {
        input.checked = true;
        syncCheckedClass(input);
      });
    });
  }

  if (clearAll) {
    clearAll.addEventListener('click', function () {
      grid.querySelectorAll('input[type="checkbox"]').forEach(function (input) {
        input.checked = false;
        syncCheckedClass(input);
      });
    });
  }
})();
</script>
</html>
