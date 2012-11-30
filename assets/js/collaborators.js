
$(document).on("ready page:load", function() {
  var typeahead_searching;
  typeahead_searching = false;
  return $("#add-collaborator-form input[name=email]").typeahead({
    minLength: 3,
    source: function(query, process) {
      clearTimeout(typeahead_searching);
      return typeahead_searching = setTimeout(function() {
        var existing_collaborators;
        existing_collaborators = [];
        $(".collaborators-table td.email").each(function() {
          return existing_collaborators.push($(this).text());
        });
        return $.ajax({
          url: "/officers/typeahead",
          data: {
            query: query
          },
          success: function(data) {
            data = $.grep(data, function(value) {
              return existing_collaborators.indexOf(value) === -1;
            });
            return process(data);
          }
        });
      }, 200);
    }
  });
});
