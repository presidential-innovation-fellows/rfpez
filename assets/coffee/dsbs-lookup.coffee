$(document).on "ready pjax:success", ->

  $("[data-dsbs-user-id]").each ->
    el = $(this)
    user_id = el.data('dsbs-user-id')
    el.on 'load-dsbs', ->
      $.ajax
        url: "http://rfpez-apis.presidentialinnovationfellows.org/bizs?user_id=#{user_id}"
        dataType: "json"
        success: (data) ->
          result = data.results[0]
          if !result
            el.removeClass('loading')
            el.addClass('no-certs')
          else
            if result.user_id is user_id
              el.removeClass('loading')
              for key of result
                el.find("[data-key=#{key}]").text(result[key])


    if el.data('defer') is false
      el.trigger('load-dsbs')