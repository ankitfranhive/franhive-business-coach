<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
(function () {
  if (window.CKEDITOR) {
    CKEDITOR.config.versionCheck = false;
    CKEDITOR.config.allowedContent = true;
    CKEDITOR.config.extraAllowedContent = '*(*);*{*}[*]';
    CKEDITOR.config.disallowedContent = '';
    CKEDITOR.config.fillEmptyBlocks = false;
    CKEDITOR.config.entities = false;
    CKEDITOR.config.basicEntities = true;
  }

  function editorConfig() {
    return {
      height: 320,
      allowedContent: true,
      extraAllowedContent: '*(*);*{*}[*]',
      disallowedContent: '',
      fillEmptyBlocks: false,
      entities: false,
      basicEntities: true,
      versionCheck: false,
      fullPage: false,
      toolbar: [
        { name: 'document', items: ['Source', '-', 'Preview'] },
        { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
        { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'] },
        { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'JustifyLeft', 'JustifyCenter', 'JustifyRight'] },
        { name: 'links', items: ['Link', 'Unlink'] },
        { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
        { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
        { name: 'colors', items: ['TextColor', 'BGColor'] },
        { name: 'tools', items: ['Maximize'] }
      ]
    };
  }

  function initRichTextEditors() {
    if (!window.CKEDITOR) return;
    document.querySelectorAll('textarea.js-richtext').forEach(function (ta) {
      if (ta.getAttribute('data-cke-initialized') === '1') return;
      CKEDITOR.replace(ta, editorConfig());
      ta.setAttribute('data-cke-initialized', '1');
    });
  }

  function syncEditorsToTextareas() {
    if (!window.CKEDITOR) return;
    for (var id in CKEDITOR.instances) {
      if (!Object.prototype.hasOwnProperty.call(CKEDITOR.instances, id)) continue;
      CKEDITOR.instances[id].updateElement();
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRichTextEditors);
  } else {
    initRichTextEditors();
  }

  document.addEventListener('submit', function (e) {
    if (!window.CKEDITOR) return;
    var form = e.target;
    if (!form || form.tagName !== 'FORM') return;

    var needsSourceFlush = false;
    for (var id in CKEDITOR.instances) {
      if (!Object.prototype.hasOwnProperty.call(CKEDITOR.instances, id)) continue;
      var ed = CKEDITOR.instances[id];
      if (ed && ed.mode === 'source') {
        needsSourceFlush = true;
        break;
      }
    }

    if (needsSourceFlush) {
      e.preventDefault();
      e.stopImmediatePropagation();
      var ids = Object.keys(CKEDITOR.instances);
      var i = 0;
      function next() {
        if (i >= ids.length) {
          syncEditorsToTextareas();
          if (typeof form.requestSubmit === 'function') {
            form.requestSubmit();
          } else {
            HTMLFormElement.prototype.submit.call(form);
          }
          return;
        }
        var ed = CKEDITOR.instances[ids[i++]];
        if (!ed) {
          next();
          return;
        }
        if (ed.mode === 'source') {
          ed.setMode('wysiwyg', next);
        } else {
          next();
        }
      }
      next();
      return;
    }

    syncEditorsToTextareas();
  }, true);
})();
</script>
