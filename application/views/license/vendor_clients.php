<div class="card">
    <h3>New client</h3>
    <form method="post" action="<?= htmlspecialchars(base_url($slug . '/client_save')); ?>">
        <div class="grid">
            <div><label>Name</label><input name="client_name" required></div>
            <div><label>Code</label><input name="client_code" required placeholder="eyd"></div>
            <div>
                <label>Status</label>
                <select name="status">
                    <option value="active">active</option>
                    <option value="grace">grace</option>
                    <option value="suspended">suspended</option>
                    <option value="cancelled">cancelled</option>
                </select>
            </div>
            <div><label>Notes</label><input name="notes"></div>
        </div>
        <p><button type="submit">Create client</button></p>
    </form>
</div>

<table>
    <thead>
        <tr><th>Client</th><th>Code</th><th>Plan</th><th>Expiry</th><th>Days</th><th>Status</th><th></th></tr>
    </thead>
    <tbody>
    <?php if (empty($clients)): ?>
        <tr><td colspan="7" class="muted">No clients yet.</td></tr>
    <?php else: foreach ($clients as $c):
        $days = isset($c['expiry_date']) && $c['expiry_date'] ? (int)floor((strtotime($c['expiry_date']) - strtotime(date('Y-m-d'))) / 86400) : null;
    ?>
        <tr>
            <td><?= htmlspecialchars($c['client_name']); ?></td>
            <td><?= htmlspecialchars($c['client_code']); ?></td>
            <td><?= htmlspecialchars($c['plan_name'] ?? ''); ?></td>
            <td><?= htmlspecialchars($c['expiry_date'] ?? ''); ?></td>
            <td><?= $days === null ? '—' : $days; ?></td>
            <td><?= htmlspecialchars($c['license_status'] ?? $c['status']); ?></td>
            <td><a href="<?= htmlspecialchars(base_url($slug . '/edit/' . $c['id'])); ?>">Open</a></td>
        </tr>
    <?php endforeach; endif; ?>
    </tbody>
</table>
