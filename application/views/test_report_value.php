<!DOCTYPE html>
<html>
<?php $this->load->view('includes/header'); ?>

<?php
$counts = $choice_counts ?? ['me' => 0, 'dont_care' => 0, 'not_me' => 0];
$meTotal = (float)($weighted_me_total ?? 0);
$notMeTotal = (float)($weighted_not_me_total ?? 0);
$attempted = (int)($attempted ?? 0);
$response_id = isset($response_id) ? (int)$response_id : (int)($user_data['ID'] ?? $user_data['id'] ?? 0);
$is_eval = (int)($user_data['IS_EVAL'] ?? $user_data['is_eval'] ?? 0);
$eval_submit_url = !empty($eval_submit_url)
    ? $eval_submit_url
    : base_url('submit-test-eval/' . $response_id);
?>

<style>
    :root {
        --vt-me: #2563eb;
        --vt-neutral: #64748b;
        --vt-not: #7c3aed;
        --vt-bg: #f1f5f9;
        --vt-card: #ffffff;
        --vt-accent: #0f766e;
    }
    body { background: var(--vt-bg); }
    .vt-wrap { max-width: 1100px; margin: 0 auto; }
    .vt-hero {
        background: linear-gradient(135deg, #0f766e 0%, #2563eb 55%, #7c3aed 100%);
        color: #fff;
        border-radius: 16px;
        padding: 28px 32px;
        margin-bottom: 24px;
        box-shadow: 0 12px 40px rgba(15, 118, 110, 0.25);
    }
    .vt-hero h2 { margin: 0 0 8px; font-weight: 700; }
    .vt-hero p { margin: 0; opacity: 0.92; }
    .vt-user {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 16px;
    }
    .vt-user img {
        width: 72px; height: 72px; border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.85);
        object-fit: cover;
    }
    .vt-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }
    .vt-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .vt-card {
        background: var(--vt-card);
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
        border: 1px solid #e2e8f0;
    }
    .vt-card h5 {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
        margin: 0 0 8px;
    }
    .vt-card .vt-num {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1.1;
    }
    .vt-card.me .vt-num { color: var(--vt-me); }
    .vt-card.neutral .vt-num { color: var(--vt-neutral); }
    .vt-card.not .vt-num { color: var(--vt-not); }
    .vt-card.weight-me { border-top: 4px solid var(--vt-me); }
    .vt-card.weight-not { border-top: 4px solid var(--vt-not); }
    .vt-note {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        font-size: 0.95rem;
    }
    .vt-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.06);
    }
    .vt-table th {
        background: #0f766e;
        color: #fff;
        padding: 12px 14px;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .vt-table td {
        padding: 12px 14px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }
    .vt-table tr:last-child td { border-bottom: none; }
    .vt-pill {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .vt-pill.me { background: #dbeafe; color: #1d4ed8; }
    .vt-pill.neutral { background: #f1f5f9; color: #475569; }
    .vt-pill.not { background: #ede9fe; color: #6d28d9; }
    .vt-excluded { color: #94a3b8; font-style: italic; }
</style>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px vt-wrap">

            <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success"><?= html_escape($this->session->flashdata('success')) ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger"><?= html_escape($this->session->flashdata('error')) ?></div>
            <?php endif; ?>

            <div class="vt-actions">
                <a class="btn btn-sm btn-outline-primary" href="<?= base_url('admin-test-report-pdf/' . $response_id) ?>">
                    Download PDF
                </a>
                <?php if ($response_id > 0 && $is_eval === 0): ?>
                    <a class="btn btn-sm btn-success"
                       href="<?= html_escape($eval_submit_url) ?>"
                       onclick="return confirm('Mark as evaluated and release results to the student?');">
                        Mark as evaluated
                    </a>
                <?php elseif ($is_eval === 1): ?>
                    <button type="button" class="btn btn-sm btn-success" disabled>Evaluated</button>
                <?php endif; ?>
            </div>

            <div class="vt-hero">
                <h2>Value Test Report</h2>
                <p>Responses are weighted by question marks. &ldquo;I don&rsquo;t care&rdquo; is counted but excluded from Me / Not me totals.</p>
                <div class="vt-user">
                    <img src="<?= htmlspecialchars($user_data['PROFILE_PICTURE'] ?: base_url('vendors/images/photo1.jpg')) ?>" alt="">
                    <div>
                        <strong><?= htmlspecialchars($user_data['NAME'] ?? '') ?></strong><br>
                        <?= htmlspecialchars($user_data['EMAIL'] ?? '') ?><br>
                        <span class="badge badge-light mt-1"><?= htmlspecialchars($user_data['TEST_NAME'] ?? '') ?></span>
                    </div>
                </div>
            </div>

            <div class="vt-note">
                <strong>How scoring works:</strong>
                Choosing <strong>Me</strong> adds that question&rsquo;s marks to the <em>Me</em> total;
                <strong>Not me</strong> adds marks to the <em>Not me</em> total.
                <strong>I don&rsquo;t care</strong> does not add to either weighted total.
                Questions with only two options (Me / Not me) are scored by option text, not position alone.
                There is no right or wrong answer.
            </div>

            <h5 class="mb-3 text-secondary">Choice distribution (all responses)</h5>
            <div class="vt-grid">
                <div class="vt-card me">
                    <h5>Me</h5>
                    <div class="vt-num"><?= (int)$counts['me'] ?></div>
                    <small class="text-muted">times selected</small>
                </div>
                <div class="vt-card neutral">
                    <h5>I don&rsquo;t care</h5>
                    <div class="vt-num"><?= (int)$counts['dont_care'] ?></div>
                    <small class="text-muted">excluded from weighted totals</small>
                </div>
                <div class="vt-card not">
                    <h5>Not me</h5>
                    <div class="vt-num"><?= (int)$counts['not_me'] ?></div>
                    <small class="text-muted">times selected</small>
                </div>
            </div>

            <h5 class="mb-3 text-secondary">Weighted totals</h5>
            <div class="vt-grid">
                <div class="vt-card weight-me">
                    <h5>Me weighted total</h5>
                    <div class="vt-num"><?= number_format($meTotal, 2) ?></div>
                </div>
                <div class="vt-card weight-not">
                    <h5>Not me weighted total</h5>
                    <div class="vt-num"><?= number_format($notMeTotal, 2) ?></div>
                </div>
                <div class="vt-card">
                    <h5>Questions answered</h5>
                    <div class="vt-num" style="color:var(--vt-accent); font-size:1.75rem;"><?= $attempted ?></div>
                </div>
            </div>

            <?php if (!empty($has_section_analytics) && !empty($sections_summary)): ?>
            <h5 class="mb-3 text-secondary">By section (Me / I don&rsquo;t care / Not me)</h5>
            <div class="table-responsive mb-4">
                <table class="vt-table">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Value #</th>
                            <th>Me</th>
                            <th>I don&rsquo;t care</th>
                            <th>Not me</th>
                            <th>Me weighted</th>
                            <th>Not me weighted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sections_summary as $sec): $c = $sec['counts'] ?? []; ?>
                        <tr>
                            <td><?= htmlspecialchars($sec['section_name'] ?? '') ?></td>
                            <td><?= (int)($sec['value_number'] ?? 0) ?></td>
                            <td><?= (int)($c['me'] ?? 0) ?></td>
                            <td><?= (int)($c['dont_care'] ?? 0) ?></td>
                            <td><?= (int)($c['not_me'] ?? 0) ?></td>
                            <td><?= number_format((float)($sec['weighted_me'] ?? 0), 2) ?></td>
                            <td><?= number_format((float)($sec['weighted_not_me'] ?? 0), 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($values_summary)): ?>
            <h5 class="mb-3 text-secondary">By value number</h5>
            <div class="table-responsive mb-4">
                <table class="vt-table">
                    <thead><tr><th>Value</th><th>Section</th><th>Me</th><th>I don&rsquo;t care</th><th>Not me</th></tr></thead>
                    <tbody>
                        <?php foreach ($values_summary as $v): $c = $v['counts'] ?? []; ?>
                        <tr>
                            <td><?= (int)($v['value_number'] ?? 0) ?></td>
                            <td><?= htmlspecialchars($v['section_name'] ?? '') ?></td>
                            <td><?= (int)($c['me'] ?? 0) ?></td>
                            <td><?= (int)($c['dont_care'] ?? 0) ?></td>
                            <td><?= (int)($c['not_me'] ?? 0) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php
            $evenC = $even_values_summary ?? [];
            $oddC = $odd_values_summary ?? [];
            ?>
            <div class="vt-grid mb-4">
                <div class="vt-card">
                    <h5>Even values (2, 4, 6…)</h5>
                    <p class="mb-1">Me: <strong><?= (int)($evenC['me'] ?? 0) ?></strong></p>
                    <p class="mb-1">I don&rsquo;t care: <strong><?= (int)($evenC['dont_care'] ?? 0) ?></strong></p>
                    <p class="mb-0">Not me: <strong><?= (int)($evenC['not_me'] ?? 0) ?></strong></p>
                </div>
                <div class="vt-card">
                    <h5>Odd values (3, 5, 7…)</h5>
                    <p class="mb-1">Me: <strong><?= (int)($oddC['me'] ?? 0) ?></strong></p>
                    <p class="mb-1">I don&rsquo;t care: <strong><?= (int)($oddC['dont_care'] ?? 0) ?></strong></p>
                    <p class="mb-0">Not me: <strong><?= (int)($oddC['not_me'] ?? 0) ?></strong></p>
                </div>
            </div>
            <?php endif; ?>

            <h5 class="mb-3 text-secondary">Question-by-question</h5>
            <div class="table-responsive">
                <table class="vt-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <?php if (!empty($has_section_analytics)): ?><th>Section</th><?php endif; ?>
                            <th>Question</th>
                            <th>Response</th>
                            <th>Q marks</th>
                            <th>Weighted</th>
                            <th>In total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach (($question_analysis ?? []) as $qa):
                            $bucket = (string)($qa['choice_bucket'] ?? '');
                            $pillClass = $bucket === 'me' ? 'me' : ($bucket === 'not_me' ? 'not' : 'neutral');
                        ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <?php if (!empty($has_section_analytics)): ?>
                                <td><?= htmlspecialchars($qa['section_name'] ?? '—') ?><?php if (!empty($qa['value_number'])): ?> <small class="text-muted">(V<?= (int)$qa['value_number'] ?>)</small><?php endif; ?></td>
                                <?php endif; ?>
                                <td><?= strip_tags($qa['question_text'] ?? '') ?></td>
                                <td>
                                    <span class="vt-pill <?= $pillClass ?>"><?= htmlspecialchars($qa['your_answer'] ?? '') ?></span>
                                </td>
                                <td><?= number_format((float)($qa['question_marks'] ?? 0), 2) ?></td>
                                <td><?= number_format((float)($qa['weighted_score'] ?? 0), 2) ?></td>
                                <td>
                                    <?php if ($bucket === 'dont_care'): ?>
                                        <span class="vt-excluded">Excluded</span>
                                    <?php else: ?>
                                        <?= htmlspecialchars($qa['counts_toward'] ?? '') ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($question_analysis)): ?>
                            <tr><td colspan="6" class="text-center text-muted">No responses recorded.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

</body>
</html>
