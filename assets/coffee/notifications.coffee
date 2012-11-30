Rfpez.unread_notification_count = ->
  parseInt($(".notification-nav-item .unread-notification-badge").text())

Rfpez.update_notification_badge = (count) ->
  nav_item = $(".notification-nav-item")
  badge = nav_item.find(".unread-notification-badge")
  if count > 0
    badge.text(count)
    badge.removeClass('hide')
  else
    badge.text(0)
    badge.addClass('hide')

Rfpez.view_notification_payload = (key, val, mark_as) ->
  mark_as ||= "read"
  return if mark_as is "read" and Rfpez.unread_notification_count() is 0
  $.ajax
    url: "/account/viewnotifications/#{key}/#{val}"
    type: "PUT"
    data:
      action: mark_as
    success: (data) ->
      Rfpez.update_notification_badge(data.unread_count)
      reset_notification_dropdown()

render_notification = (notification) ->
  """
  <li class="notification #{if notification.object.read is '0' then 'unread' else 'read'}">
    <a href="#{notification.parsed.link}">
      <span class="line1">#{notification.parsed.subject}</span>
      <span class="timeago" title="#{notification.parsed.timestamp}"></span>
    </a>
  </li>
  """

notifications_loaded = false

reset_notification_dropdown = ->
  notifications_loaded = false
  $("#notifications-dropdown").addClass('loading')
  $("#notifications-dropdown").html('')


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

$(document).on "ready page:load pjax:popstate", ->

  notifications_loaded = false

  $("#notifications-dropdown-trigger").on "click", ->
    return if notifications_loaded
    $.ajax
      url: "/notifications/json"
      dataType: "json"
      success: (data) ->
        if data.status is "success"
          if data.count < 1
            return $("#notifications-dropdown").removeClass('loading').addClass('none')
          str = ""
          $(data.results).each ->
            str += render_notification(this)
          str += """
            <li class="view-all"><a href="/notifications">view all #{data.count} notifications</a></li>
          """
          $("#notifications-dropdown").removeClass("loading")
          $("#notifications-dropdown").html(str)
          $("#notifications-dropdown span.timeago").timeago()
          notifications_loaded = true

