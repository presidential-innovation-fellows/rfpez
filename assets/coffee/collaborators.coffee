$(document).on "ready page:load", ->

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