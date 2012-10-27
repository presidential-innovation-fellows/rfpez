hide_already_selected_sections = ->
  selected_section_ids = []

  $(".selected-sections .section").each ->
    selected_section_ids.push $(this).data('section-id')

  $(".available-sections-table .section").each ->
    el = $(this)
    section_id = el.data('section-id')
    if selected_section_ids.indexOf(section_id) isnt -1
      el.hide()
    else
      el.show()


$(document).on "ready pjax:success", ->
  hide_already_selected_sections()

$(document).on "click", ".section .remove-button", (e) ->
  e.preventDefault()
  el = $(this)
  el.button('loading')
  $.ajax
    url: el.data('href')
    type: "DELETE"
    success: (data) ->
      new_selected_sections = $(data.selected_sections_html)
      $(".selected-sections").replaceWith(new_selected_sections)
      hide_already_selected_sections()

$(document).on "click", ".section .add-button", (e) ->
  e.preventDefault()
  el = $(this)
  el.button('loading')
  $.ajax
    url: el.data('href')
    type: "POST"
    success: (data) ->
      new_selected_sections = $(data.selected_sections_html)
      $(".selected-sections").replaceWith(new_selected_sections)
      el.button('reset')
      hide_already_selected_sections()

$(document).on "click", ".add-section-button", ->
  $("#edit-section-modal").find(".modal-header h3").text("Add Section")
  $("#edit-section-modal").modal('show')

$(document).on "submit", "#edit-section-form", (e) ->
  e.preventDefault()
  el = $(this)
  button = el.find(".save-button")
  button.button('loading')
  el.ajaxSubmit
    success: (data) ->
      new_sections_for_editing = $(data.sections_for_editing_html)
      $(".sections-for-editing").replaceWith(new_sections_for_editing)
      $("#edit-section-modal").modal('hide')
      button.button('reset')