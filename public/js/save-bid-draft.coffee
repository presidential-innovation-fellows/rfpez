draft_saved = true
save_draft_button = $("#save-draft-button")
save_draft_button.button('loading')

form_update_handler = ->
  draft_saved = false
  save_draft_button.button('reset')

save_draft = ->
  return if draft_saved is true
  form = $(".new-bid-form")
  form.find("input[name=submit_now]").val('false')
  form.ajaxSubmit()
  form.find("input[name=submit_now]").val('true')
  draft_saved = true
  save_draft_button.button('loading')

$(document).on "click", "#save-draft-button", save_draft
$(document).on "input", ".new-bid-form :input", form_update_handler
$(document).on "click", "#add-deliverable-button, .remove-deliverable", form_update_handler

window.setInterval(save_draft, 5000)