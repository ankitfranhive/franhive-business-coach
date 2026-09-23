<div class="card">
    <h3><?= htmlspecialchars($client['client_name']); ?> <span class="muted"><?= htmlspecialchars($client['client_code']); ?></span></h3>
    <form method="post" action="<?= htmlspecialchars(base_url($slug . '/client_save')); ?>">
        <input type="hidden" name="id" value="<?= (int)$client['id']; ?>">
        <div class="grid">
            <div><label>Name</label><input name="client_name" value="<?= htmlspecialchars($client['client_name']); ?>" required></div>
            <div><label>Code</label><input name="client_code" value="<?= htmlspecialchars($client['client_code']); ?>" required></div>
            <div>
                <label>Status</label>
                <select name="status">
                    <?php foreach (array('active','grace','suspended','cancelled') as $st): ?>
                        <option value="<?= $st; ?>" <?= ($client['status'] === $st) ? 'selected' : ''; ?>><?= $st; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div><label>Notes</label><input name="notes" value="<?= htmlspecialchars($client['notes'] ?? ''); ?>"></div>
        </div>
        <p><button type="submit">Save client</button></p>
    </form>
</div>

<div class="card">
    <h3>License</h3>
    <form method="post" action="<?= htmlspecialchars(base_url($slug . '/license_save')); ?>">
        <input type="hidden" name="client_id" value="<?= (int)$client['id']; ?>">
        <input type="hidden" name="license_id" value="<?= (int)($license['id'] ?? 0); ?>">
        <div class="grid">
            <div><label>Plan</label><input name="plan_name" value="<?= htmlspecialchars($license['plan_name'] ?? ''); ?>"></div>
            <div>
                <label>Status</label>
                <select name="status">
                    <?php foreach (array('active','grace','suspended','cancelled') as $st): ?>
                        <option value="<?= $st; ?>" <?= (($license['status'] ?? 'active') === $st) ? 'selected' : ''; ?>><?= $st; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div><label>Start</label><input type="date" name="start_date" value="<?= htmlspecialchars($license['start_date'] ?? date('Y-m-d')); ?>" required></div>
            <div><label>Expiry</label><input type="date" name="expiry_date" value="<?= htmlspecialchars($license['expiry_date'] ?? date('Y-m-d', strtotime('+1 year'))); ?>" required></div>
            <div><label>Grace days</label><input type="number" name="grace_period_days" min="0" value="<?= (int)($license['grace_period_days'] ?? 3); ?>"></div>
        </div>
        <p class="muted">Saving re-signs the token. Editing expiry in the database without this screen will fail verification.</p>
        <p><button type="submit">Save and sign license</button>
            <a class="btn" href="<?= htmlspecialchars(base_url($slug . '/notify_test/' . (int)$client['id'])); ?>">Send test notification</a>
        </p>
        <?php if (!empty($license['signed_token'])): ?>
            <p class="muted">Token issued <?= htmlspecialchars($license['token_issued_at'] ?? ''); ?></p>
            <textarea rows="3" readonly><?= htmlspecialchars($license['signed_token']); ?></textarea>
        <?php endif; ?>
    </form>
</div>

<div class="card">
    <h3>Notification contacts</h3>
    <form method="post" action="<?= htmlspecialchars(base_url($slug . '/contact_save')); ?>">
        <input type="hidden" name="client_id" value="<?= (int)$client['id']; ?>">
        <div class="grid">
            <div><label>Name</label><input name="name" required></div>
            <div><label>Email</label><input type="email" name="email"></div>
            <div><label>Phone</label><input name="phone"></div>
            <div><label>Role label</label><input name="role_label" placeholder="Billing Admin"></div>
        </div>
        <p><label><input type="checkbox" name="is_active" value="1" checked> Active</label></p>
        <p><button type="submit">Add contact</button></p>
    </form>
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th></th></tr></thead>
        <tbody>
        <?php if (empty($contacts)): ?>
            <tr><td colspan="5" class="muted">None yet.</td></tr>
        <?php else: foreach ($contacts as $ct): ?>
            <tr>
                <td><?= htmlspecialchars($ct['name']); ?></td>
                <td><?= htmlspecialchars($ct['email']); ?></td>
                <td><?= htmlspecialchars($ct['phone']); ?></td>
                <td><?= htmlspecialchars($ct['role_label']); ?><?= empty($ct['is_active']) ? ' (off)' : ''; ?></td>
                <td><a href="<?= htmlspecialchars(base_url($slug . '/contact_delete/' . (int)$ct['id'] . '/' . (int)$client['id'])); ?>">Remove</a></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
