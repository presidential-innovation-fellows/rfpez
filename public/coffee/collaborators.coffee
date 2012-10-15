$(document).on "submit", "#add-collaborator-form", (e) ->
  el = $(this)
  button = el.find("button")
  e.preventDefault()
  el.ajaxSubmit
    success: (data) ->
      if data.status is "success"
        new_tr = $(data.html)
        $(".collaborators-table tbody").append(new_tr)
      else if data.status is "already exists"
        button.flash_button_message("warning", "Collaborator already exists")
      else if data.status is "dotgovonly"
        button.flash_button_message("warning", ".gov email addresses only!")
      else
        button.flash_button_message("warning", "Error occurred")
      el.resetForm()

$(document).on "click", ".remove-collaborator-button", (e) ->
  e.preventDefault()
  el = $(this)
  $.ajax
    url: el.attr("href")
    type: "delete"
    success: ->
      el.closest("tr").remove()

$ ->
  typeahead_searching = false

  $("#add-collaborator-form input[name=email]").typeahead
    minLength: 3
    source: (query, process) ->
      clearTimeout(typeahead_searching)

      typeahead_searching = setTimeout ->
        existing_collaborators = []
        $(".collaborators-table td.email").each ->
          existing_collaborators.push $(this).text()

        $.ajax
          url: "/officers/typeahead"
          data:
            query: query
          success: (data) ->
            data = $.grep data, (value) ->
              return existing_collaborators.indexOf(value) is -1

            return process(data)
      , 200