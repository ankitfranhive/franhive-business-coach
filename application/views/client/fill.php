<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title><?= html_escape($template->title) ?></title>
  <style>
    body{font-family:Arial;max-width:900px;margin:24px auto;padding:0 16px;}
    .box{border:1px solid #ddd;border-radius:10px;padding:16px;}
    label{display:block;margin-top:12px;font-weight:700;}
    input, textarea, select{width:100%;padding:10px;margin-top:6px;box-sizing:border-box;}
    .row{display:flex;gap:10px;}
    .row > div{flex:1;}
    .err{color:#b00;font-size:13px;}
    canvas{border:1px solid #ccc;border-radius:6px;width:100%;height:160px;}
    button{padding:10px 16px;margin-top:14px;}
  </style>
</head>
<body>

  <h2><?= html_escape($template->heading ?: $template->title) ?></h2>
  <?php if ($template->subheading): ?><p><b><?= html_escape($template->subheading) ?></b></p><?php endif; ?>

  <div class="box">
    <?php if ($template->body_html): ?>
      <div><?= $template->body_html ?></div>
      <hr>
    <?php endif; ?>

    <?= validation_errors('<div class="err">','</div>'); ?>

    <form method="post" onsubmit="return beforeSubmit();">
      <?php foreach($fields as $f):
        $label = $overrides['labels'][$f->name] ?? $f->label;
        $default = $prefill[$f->name] ?? '';
      ?>
        <label><?= html_escape($label) ?><?= ((int)$f->is_required===1) ? ' *' : '' ?></label>

        <?php if ($f->type === 'textarea'): ?>
          <textarea name="<?= html_escape($f->name) ?>" rows="4"><?= set_value($f->name, $default) ?></textarea>
        <?php else: ?>
          <input type="<?= ($f->type==='email'?'email':($f->type==='date'?'date':'text')) ?>"
                 name="<?= html_escape($f->name) ?>"
                 value="<?= set_value($f->name, $default) ?>">
        <?php endif; ?>
      <?php endforeach; ?>

      <label style="margin-top:18px;">
        <input type="checkbox" name="agree" required>
        <?= html_escape($agree_text) ?>
      </label>

      <h3 style="margin-top:18px;">Signature</h3>
      <canvas id="sig"></canvas>
      <input type="hidden" name="signature_data" id="signature_data">

      <button type="button" onclick="clearSig()">Clear Signature</button>
      <button type="submit">Submit</button>
    </form>
  </div>

<script>
  const canvas = document.getElementById('sig');
  const ctx = canvas.getContext('2d');

  // fix canvas size
  function resizeCanvas(){
    const rect = canvas.getBoundingClientRect();
    canvas.width = rect.width;
    canvas.height = rect.height;
  }
  window.addEventListener('load', resizeCanvas);
  window.addEventListener('resize', resizeCanvas);

  let drawing=false, last=null;

  function pos(e){
    const r = canvas.getBoundingClientRect();
    const x = (e.touches ? e.touches[0].clientX : e.clientX) - r.left;
    const y = (e.touches ? e.touches[0].clientY : e.clientY) - r.top;
    return {x,y};
  }

  function start(e){ drawing=true; last=pos(e); e.preventDefault(); }
  function move(e){
    if(!drawing) return;
    const p = pos(e);
    ctx.beginPath();
    ctx.moveTo(last.x,last.y);
    ctx.lineTo(p.x,p.y);
    ctx.lineWidth = 2;
    ctx.stroke();
    last = p;
    e.preventDefault();
  }
  function end(){ drawing=false; last=null; }

  canvas.addEventListener('mousedown', start);
  canvas.addEventListener('mousemove', move);
  window.addEventListener('mouseup', end);

  canvas.addEventListener('touchstart', start, {passive:false});
  canvas.addEventListener('touchmove', move, {passive:false});
  window.addEventListener('touchend', end);

  function clearSig(){
    ctx.clearRect(0,0,canvas.width,canvas.height);
  }

  function beforeSubmit(){
    document.getElementById('signature_data').value = canvas.toDataURL('image/png');
    return true;
  }
</script>
</body>
</html>
