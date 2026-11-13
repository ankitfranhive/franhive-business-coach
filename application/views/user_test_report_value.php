<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px" style="max-width:900px; margin:0 auto;">

            <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:15px;">
                <a href="<?= base_url('my-test') ?>" class="btn btn-sm btn-outline-secondary">&larr; Back</a>
                <?php if (!empty($response_id) && !empty($result_groups)): ?>
                    <a href="<?= base_url('my-test-report-pdf/' . (int)$response_id) ?>" class="btn btn-sm btn-outline-primary">Download PDF</a>
                <?php endif; ?>
            </div>

            <div style="background:#fff; border-radius:12px; padding:32px 28px; box-shadow:0 4px 16px rgba(0,0,0,0.06); overflow-x:auto;">
                <?php $this->load->view('partials/value_test_result_table', [
                    'result_groups' => $result_groups ?? [],
                    'student_name'  => $student_name ?? '',
                    'result_date'   => $result_date ?? '',
                ]); ?>
            </div>

        </div>
    </div>
</div>

<?php $this->load->view('includes/footer'); ?>
</body>
</html>
