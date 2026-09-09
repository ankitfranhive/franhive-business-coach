<?php $sent_emails = isset($sent_emails) ? $sent_emails : []; ?>
<?php if (!empty($sent_emails)): ?>
<div class="table-responsive">
    <table class="table table-bordered table-striped cm-sent-table">
        <thead>
            <tr>
                <th>To</th>
                <th>Recipient</th>
                <th>Subject</th>
                <th>Template</th>
                <th>Sent at</th>
                <th>Status</th>
                <th>View</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sent_emails as $email): ?>
                <?php
                    $sent_ts = !empty($email['SEND_DATE']) ? strtotime($email['SEND_DATE']) : false;
                    $sent_display = $sent_ts ? date('d/m/y h:i A', $sent_ts) : (string)($email['SEND_DATE'] ?? '');
                    $status = strtolower((string)($email['STATUS'] ?? 'sent'));
                ?>
                <tr>
                    <td><?= htmlspecialchars($email['TO_EMAIL'] ?? '') ?></td>
                    <td><?= htmlspecialchars($email['TO_NAME'] ?? $email['RECIPIENT_NAME'] ?? '') ?></td>
                    <td><?= htmlspecialchars($email['SUBJECT'] ?? '') ?></td>
                    <td><?= htmlspecialchars($email['TEMPLATE_NAME'] ?? '') ?></td>
                    <td><?= htmlspecialchars($sent_display) ?></td>
                    <td><span class="cm-sent-status"><?= htmlspecialchars(ucfirst($status !== '' ? $status : 'sent')) ?></span></td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="<?= base_url('view-lead-email/' . (int)$email['ID']); ?>">
                            Open email
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
    <p class="text-muted mb-0">No emails have been sent to this lead yet.</p>
<?php endif; ?>
