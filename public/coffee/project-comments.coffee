$(document).on "submit", "#add-comment-form", (e) ->
  e.preventDefault()
  el = $(this)
  return if !el.find("textarea").val()
  el.ajaxSubmit
    success: (data) ->
      if data.status is "success"
        new_comment = $(data.html)
        new_comment.find("span.timeago").timeago()
        $(".comments-list").append(new_comment)
        el.resetForm()

$(document).on "click", ".delete-comment", (e) ->
  e.preventDefault()
  el = $(this)
  $.ajax
    url: el.attr('href')
    success: (data) ->
      if data.status is "success"
        el.closest(".comment").fadeOut 300, ->
          el.remove()