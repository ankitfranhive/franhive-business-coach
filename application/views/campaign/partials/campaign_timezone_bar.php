<?php
$campaign_timezone = campaign_normalize_timezone($campaign_timezone ?? campaign_default_timezone());
?>
<div class="cm-tz-bar">
    <div class="cm-tz-clocks" id="campaignTzClocks">
        <?php foreach (campaign_timezone_clocks() as $tz_id => $tz_meta): ?>
            <div class="cm-tz-chip" data-tz="<?= htmlspecialchars($tz_id) ?>" data-abbr="<?= htmlspecialchars($tz_meta['abbr']) ?>">
                <span class="cm-tz-name"><?= htmlspecialchars($tz_meta['label']) ?>:</span>
                <span class="cm-tz-live">--:--:-- -- (<?= htmlspecialchars($tz_meta['abbr']) ?>)</span>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="form-group mb-0" style="max-width:360px;">
        <label>Timezone for these send times</label>
        <?= campaign_timezone_select('TIMEZONE', $campaign_timezone, 'campaign-timezone') ?>
        <p class="cm-tz-help">Default is Melbourne (AET). The date and time you set for each template use this timezone. Cron sends when that local time arrives.</p>
    </div>
</div>
<script>
(function () {
    function tickClocks() {
        var now = new Date();
        document.querySelectorAll('#campaignTzClocks .cm-tz-chip').forEach(function (chip) {
            var tz = chip.getAttribute('data-tz');
            var abbr = chip.getAttribute('data-abbr');
            var live = chip.querySelector('.cm-tz-live');
            if (!live) return;
            try {
                var time = new Intl.DateTimeFormat('en-US', {
                    timeZone: tz,
                    hour12: true,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                }).format(now);
                live.textContent = time + ' (' + abbr + ')';
            } catch (e) {
                live.textContent = now.toLocaleTimeString() + ' (' + abbr + ')';
            }
        });
    }
    tickClocks();
    setInterval(tickClocks, 1000);

    var campaignTz = document.querySelector('select.campaign-timezone, select[name="TIMEZONE"]');
    if (campaignTz) {
        campaignTz.addEventListener('change', function () {
            document.querySelectorAll('select.template-timezone').forEach(function (sel) {
                sel.value = campaignTz.value;
            });
        });
    }
})();
</script>
