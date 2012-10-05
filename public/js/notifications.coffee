$(document).on "click", ".notification-item .mark-as-read, .notification-item .mark-as-unread", ->
  el = $(this)
  notification_item = el.closest(".notification-item")
  data_el = el.closest("[data-notification-id]")
  notification_id = data_el.data('notification-id')
  action = if el.hasClass('mark-as-read') then 1 else 0
  $.ajax
    url: "/notifications/#{notification_id}/markasread"
    type: "PUT"
    data:
      action: action
    success: (data) ->
      if data.status is "success"
        new_notification_item = $(data.html)
        notification_item.replaceWith(new_notification_item)
