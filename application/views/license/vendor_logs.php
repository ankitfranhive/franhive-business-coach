<p><a class="btn" href="<?= htmlspecialchars(base_url($slug . '/baseline')); ?>">Seal integrity baseline</a></p>

<div class="card">
    <h3>Notifications</h3>
    <table>
        <thead><tr><th>When</th><th>Client</th><th>Type</th><th>Channel</th><th>To</th><th>Status</th></tr></thead>
        <tbody>
        <?php if (empty($notifications)): ?>
            <tr><td colspan="6" class="muted">None.</td></tr>
        <?php else: foreach ($notifications as $n): ?>
            <tr>
                <td><?= htmlspecialchars($n['sent_at']); ?></td>
                <td><?= htmlspecialchars($n['client_name'] ?? ''); ?></td>
                <td><?= htmlspecialchars($n['notification_type']); ?></td>
                <td><?= htmlspecialchars($n['channel']); ?></td>
                <td><?= htmlspecialchars($n['recipient']); ?></td>
                <td><?= htmlspecialchars($n['status']); ?></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Integrity</h3>
    <table>
        <thead><tr><th>When</th><th>File</th><th>Match</th><th>Alert</th></tr></thead>
        <tbody>
        <?php if (empty($integrity)): ?>
            <tr><td colspan="4" class="muted">None.</td></tr>
        <?php else: foreach ($integrity as $n): ?>
            <tr>
                <td><?= htmlspecialchars($n['check_time']); ?></td>
                <td><?= htmlspecialchars($n['file_path']); ?></td>
                <td><?= !empty($n['match']) ? 'yes' : 'NO'; ?></td>
                <td><?= !empty($n['alert_sent']) ? 'yes' : 'no'; ?></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Heartbeats</h3>
    <table>
        <thead><tr><th>When</th><th>Status</th><th>Hash</th></tr></thead>
        <tbody>
        <?php if (empty($heartbeats)): ?>
            <tr><td colspan="3" class="muted">None.</td></tr>
        <?php else: foreach ($heartbeats as $n): ?>
            <tr>
                <td><?= htmlspecialchars($n['checked_at']); ?></td>
                <td><?= htmlspecialchars($n['license_status_at_check']); ?></td>
                <td class="muted"><?= htmlspecialchars(substr($n['file_hash_at_check'], 0, 16)); ?>…</td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
