<script src="https://cdn.ckeditor.com/4.22.1/full-all/ckeditor.js"></script>
<script>
(function () {
  function initRichTextEditors() {
    if (!window.CKEDITOR) return;
    document.querySelectorAll('textarea.js-richtext').forEach(function (ta) {
      if (ta.getAttribute('data-cke-initialized') === '1') return;
      CKEDITOR.replace(ta, {
        height: 280,
        allowedContent: true,
        extraAllowedContent: '*(*);*{*}[*]',
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
      });
      ta.setAttribute('data-cke-initialized', '1');
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRichTextEditors);
  } else {
    initRichTextEditors();
  }

  document.addEventListener('submit', function () {
    if (!window.CKEDITOR) return;
    for (var id in CKEDITOR.instances) {
      if (Object.prototype.hasOwnProperty.call(CKEDITOR.instances, id)) {
        CKEDITOR.instances[id].updateElement();
      }
    }
  }, true);
})();
</script>
