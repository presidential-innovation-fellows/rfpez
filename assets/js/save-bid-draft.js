
$(document).on("ready pjax:success", function() {
  var draft_saved, form_update_handler, save_draft, save_draft_button;
  if (!Rfpez.current_page("new-bid")) {
    return;
  }
  draft_saved = true;
  save_draft_button = $("#save-draft-button");
  save_draft_button.button('loading');
  form_update_handler = function() {
    draft_saved = false;
    return save_draft_button.button('reset');
  };
  save_draft = function() {
    var form;
    if (draft_saved === true) {
      return;
    }
    form = $(".new-bid-form");
    form.find("input[name=submit_now]").val('false');
    form.ajaxSubmit();
    form.find("input[name=submit_now]").val('true');
    draft_saved = true;
    return save_draft_button.button('loading');
  };
  $("#save-draft-button").on("click", save_draft);
  $(".new-bid-form :input").on("input", form_update_handler);
  $("#add-deliverable-button, .remove-deliverable").on("click", form_update_handler);
  return window.setInterval(save_draft, 5000);
});
