// ckeditor-bootstrap-fix.js
// A Snow 2013-08-31
// Hack to fix ckeditor/bootstrap compatiability bug when ckeditor appears in a bootstrap modal dialog.
// Without this, ckeditor dialogs (e.g., the link editor) don't work because they're modals-on-top-of-a-modal.
//
// NOTE: Include this AFTER jQuery, bootstrap and ckeditor are loaded.

$.fn.modal.Constructor.prototype.enforceFocus = function() {
  modal_this = this
  $(document).on('focusin.modal', function (e) {
    if (modal_this.$element[0] !== e.target && !modal_this.$element.has(e.target).length 
    && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_select') 
    && !$(e.target.parentNode).hasClass('cke_dialog_ui_input_text')) {
      modal_this.$element.focus()
    }
  })
};
