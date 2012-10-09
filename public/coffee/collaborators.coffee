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
      else if data.status is "can't add yourself"
        button.flash_button_message("warning", "Can't add yourself")
      else
        button.flash_button_message("warning", "Error occurred")
      el.resetForm()

$(document).on "click", ".remove-collaborator-button", ->
  el = $(this)
  contract_id = el.closest("[data-contract-id]").data('contract-id')
  officer_id = el.closest("[data-officer-id]").data('officer-id')
  $.ajax
    url: "/contracts/#{contract_id}/collaborators/#{officer_id}"
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
        $.ajax
          url: "/officers/typeahead"
          data:
            query: query
          success: (data) ->
            console.log 'searched'
            return process(data)
      , 200