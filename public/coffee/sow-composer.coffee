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
