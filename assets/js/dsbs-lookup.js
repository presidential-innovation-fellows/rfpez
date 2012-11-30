
$(document).on("ready page:load", function() {
  return $("[data-dsbs-user-id]").each(function() {
    var el, user_id;
    el = $(this);
    user_id = el.data('dsbs-user-id');
    el.on('load-dsbs', function() {
      return $.ajax({
        url: "http://rfpez-apis.presidentialinnovationfellows.org/bizs?user_id=" + user_id,
        dataType: "json",
        success: function(data) {
          var key, result, _results;
          result = data.results[0];
          if (!result) {
            el.removeClass('loading');
            return el.addClass('no-certs');
          } else {
            if (result.user_id === user_id) {
              el.removeClass('loading');
              _results = [];
              for (key in result) {
                _results.push(el.find("[data-key=" + key + "]").text(result[key]));
              }
              return _results;
            }
          }
        }
      });
    });
    if (el.data('defer') === false) {
      return el.trigger('load-dsbs');
    }
  });
});
