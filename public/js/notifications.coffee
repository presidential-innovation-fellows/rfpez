Rfpez.update_notification_badge = (count) ->
  nav_item = $(".notification-nav-item")
  badge = nav_item.find(".unread-notification-badge")
  if count > 0
    badge.text(count)
    badge.removeClass('hide')
  else
    badge.addClass('hide')

Rfpez.view_notification_payload = (key, val) ->
  $.ajax
    url: "/account/viewnotifications/#{key}/#{val}"
    type: "PUT"
    success: (data) ->
      Rfpez.update_notification_badge(data.unread_count)

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
        Rfpez.update_notification_badge(data.unread_count)
