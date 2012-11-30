$(document).on "ready page:load", ->

  return unless Rfpez.current_page("new-bid")

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

  $("#save-draft-button").on "click", save_draft
  $(".new-bid-form :input").on "input", form_update_handler
  $("#add-deliverable-button, .remove-deliverable").on "click", form_update_handler

  window.setInterval(save_draft, 5000)