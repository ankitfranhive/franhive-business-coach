<?php
$partner = isset($partner) ? $partner : (getenv('CLOUDTALK_PARTNER') ?: 'lms');
$sidebarEnabled = isset($sidebarEnabled) ? (bool)$sidebarEnabled : true;
?>

<style>
  :root{
    --ct-gap: 16px;
    --ct-widget-w: 420px;   /* CloudTalk min width */
    --ct-widget-h: 700px;   /* CloudTalk min height */
    --ct-sidebar-w: 380px;
  }

  /* DOCK: holds sidebar (left) + phone (right) */
  #ct-dock{
    position: fixed;
    right: var(--ct-gap);
    bottom: var(--ct-gap);
    display: flex;
    gap: var(--ct-gap);
    align-items: flex-end;
    z-index: 10000; /* above page UI */
  }

  /* PHONE */
  #ct-widget{
    width: var(--ct-widget-w);
    height: var(--ct-widget-h);
    border: 0;
    box-shadow: 0 6px 24px rgba(0,0,0,.25);
    border-radius: 12px;
    overflow: hidden;
    background: #000;
  }
  #ct-header{
    height:36px; background:#0b2239; color:#fff; display:flex; align-items:center;
    gap:8px; padding:0 10px; font: 600 13px/1 ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial;
  }
  #ct-close{ margin-left:auto; cursor:pointer; opacity:.8 }
  #ct-iframe{ width:100%; height:calc(100% - 36px); border:0 }

  /* SIDEBAR (now left of the phone) */
  #ct-sidebar{
    width: var(--ct-sidebar-w);
    height: var(--ct-widget-h);
    background:#fff;
    border: 1px solid #e5e7eb;
    box-shadow: 0 6px 24px rgba(0,0,0,.15);
    border-radius: 12px;
    display: none;               /* hidden until opened */
    flex-direction: column;
    overflow: hidden;
    z-index: 10010;              /* a tad above phone */
  }
  #ct-sidebar.open{ display:flex; }
  #ct-sidebar-header{
    padding:12px 14px; background:#f3f4f6; border-bottom:1px solid #e5e7eb; font-weight:600;
    display:flex; align-items:center; justify-content:space-between;
  }
  #ct-sidebar-body{ padding:12px 14px; overflow:auto; }

  /* Optional: small launcher button (if you want manual toggle) */
  #ct-launcher{ position: fixed; bottom: calc(var(--ct-gap) + var(--ct-widget-h) + 10px); right: var(--ct-gap); z-index: 10001; }

  /* Responsive fallback: stack vertically if width is tight */
  @media (max-width: 1200px){
    #ct-dock{ flex-direction: column; align-items:flex-end; }
    #ct-sidebar{ width: min(420px, 95vw); height: 360px; }
  }
</style>

<!-- Optional launcher (manual open/close) -->
<div id="ct-launcher" style="display:none">
  <button type="button" class="btn btn-primary"
    onclick="document.getElementById('ct-sidebar').classList.toggle('open')">Lead Panel</button>
</div>

<div id="ct-dock" aria-label="Telephony Dock">
  <?php if ($sidebarEnabled): ?>
    <aside id="ct-sidebar" aria-hidden="true">
      <div id="ct-sidebar-header">
        Lead
        <button id="ct-sidebar-close" title="Close" style="border:0;background:transparent;font-size:18px;cursor:pointer;">✕</button>
      </div>
      <div id="ct-sidebar-body"><em>Waiting for call…</em></div>
    </aside>
  <?php endif; ?>

  <section id="ct-widget" aria-label="CloudTalk Phone">
    <div id="ct-header">
      <span>CloudTalk Phone</span>
      <span id="ct-status" style="opacity:.7;margin-left:8px;">idle</span>
      <span id="ct-close" title="Hide" onclick="document.getElementById('ct-widget').style.display='none'">✕</span>
    </div>
    <iframe id="ct-iframe"
      src="<?= 'https://phone.cloudtalk.io?partner=' . urlencode($partner) ?>"
      allow="microphone; autoplay; clipboard-write"
      referrerpolicy="no-referrer"
      loading="eager">
    </iframe>
  </section>
</div>
<script>
  var CT_DEBUG = true;

  function isTrustedOrigin(origin){
    try{ var u = new URL(origin); return u.hostname.endsWith('.cloudtalk.io'); }catch(e){ return false; }
  }
  function openSidebarUI(){
    var sb = document.getElementById('ct-sidebar');
    if (sb) sb.classList.add('open'); // no body class, no pushing phone
  }
  function closeSidebarUI(){
    var sb = document.getElementById('ct-sidebar');
    if (sb) sb.classList.remove('open');
  }
  document.addEventListener('click', function(e){
    if (e.target && e.target.id === 'ct-sidebar-close') closeSidebarUI();
  });

  function extractContact(data){
    var phone='', email='';
    if (data?.properties?.external_number) phone = data.properties.external_number;
    if (!phone && data?.properties?.number) phone = data.properties.number;
    if (!phone && data?.number) phone = data.number;
    if (!phone && data?.to) phone = data.to;

    var emails = [];
    if (Array.isArray(data?.properties?.contact?.contact_emails)) emails = data.properties.contact.contact_emails;
    if (!emails.length && Array.isArray(data?.contact_emails)) emails = data.contact_emails;
    if (!emails.length && typeof data?.email === 'string') emails = [data.email];
    if (emails.length) email = emails[0];

    return { phone: phone || '', email: email || '' };
  }

  async function openSidebarIfPossible(data){
    <?php if ($sidebarEnabled): ?>
    var c = extractContact(data);
    if (CT_DEBUG) console.log('[CT] contact guess:', c);
    if (!c.phone && !c.email) return;
    try{
      var resp = await fetch("<?= site_url('telephony/sidebar') ?>?phone="+encodeURIComponent(c.phone)+"&email="+encodeURIComponent(c.email), { headers:{'Accept':'text/html'} });
      var html = await resp.text();
      document.getElementById('ct-sidebar-body').innerHTML = html;
      openSidebarUI();
    }catch(err){ if (CT_DEBUG) console.warn('[CT] sidebar fetch failed', err); }
    <?php endif; ?>
  }

  // Listen to CloudTalk iFrame events
  window.addEventListener('message', function(e){
    if (!isTrustedOrigin(e.origin)) return;
    var data=null; try{ data = (typeof e.data==='string') ? JSON.parse(e.data) : e.data; }catch(_){ return; }
    if (!data) return;
    if (CT_DEBUG) console.log('[CT] event', data);

    var ev = (data.event || data.type || '').toLowerCase();
    if (['ringing','dialing','outgoing_call_started','call_started','connected','incoming_call'].includes(ev)){
      document.getElementById('ct-status').textContent = ev || 'active';
      openSidebarIfPossible(data);          // show sidebar on the LEFT
    }
    if (['hangup','ended','call_ended','disconnected'].includes(ev)){
      document.getElementById('ct-status').textContent = 'idle';
      closeSidebarUI();
    }
  });
</script>


<script>
// Remember sidebar state
(function(){
  const KEY = 'ct_sidebar_open';
  const sb = document.getElementById('ct-sidebar');
  const openSidebarUI = () => { sb.classList.add('open'); localStorage.setItem(KEY, '1'); };
  const closeSidebarUI = () => { sb.classList.remove('open'); localStorage.setItem(KEY, '0'); };

  // restore on load
  if (localStorage.getItem(KEY) === '1') sb.classList.add('open');

  // expose for your existing code (if names differ, adjust your calls)
  window.ctOpenSidebar = openSidebarUI;
  window.ctCloseSidebar = closeSidebarUI;

  // Hotkey: L to toggle
  document.addEventListener('keydown', (e) => {
    if (e.key.toLowerCase() === 'l' && !e.ctrlKey && !e.metaKey && !e.altKey) {
      sb.classList.toggle('open');
      localStorage.setItem(KEY, sb.classList.contains('open') ? '1' : '0');
    }
  });
})();
</script>

