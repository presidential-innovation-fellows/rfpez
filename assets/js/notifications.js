var notifications_loaded, render_notification, reset_notification_dropdown;

Rfpez.unread_notification_count = function() {
  return parseInt($(".notification-nav-item .unread-notification-badge").text());
};

Rfpez.update_notification_badge = function(count) {
  var badge, nav_item;
  nav_item = $(".notification-nav-item");
  badge = nav_item.find(".unread-notification-badge");
  if (count > 0) {
    badge.text(count);
    return badge.removeClass('hide');
  } else {
    badge.text(0);
    return badge.addClass('hide');
  }
};

Rfpez.view_notification_payload = function(key, val, mark_as) {
  mark_as || (mark_as = "read");
  if (mark_as === "read" && Rfpez.unread_notification_count() === 0) {
    return;
  }
  return $.ajax({
    url: "/account/viewnotifications/" + key + "/" + val,
    type: "PUT",
    data: {
      action: mark_as
    },
    success: function(data) {
      Rfpez.update_notification_badge(data.unread_count);
      return reset_notification_dropdown();
    }
  });
};

render_notification = function(notification) {
  return "<li class=\"notification " + (notification.object.read === '0' ? 'unread' : 'read') + "\">\n  <a href=\"" + notification.parsed.link + "\">\n    <span class=\"line1\">" + notification.parsed.subject + "</span>\n    <span class=\"timeago\" title=\"" + notification.parsed.timestamp + "\"></span>\n  </a>\n</li>";
};

notifications_loaded = false;

reset_notification_dropdown = function() {
  notifications_loaded = false;
  $("#notifications-dropdown").addClass('loading');
  return $("#notifications-dropdown").html('');
};

$(document).on("click", ".notification-item .mark-as-read, .notification-item .mark-as-unread", function() {
  var action, data_el, el, notification_id, notification_item;
  el = $(this);
  notification_item = el.closest(".notification-item");
  data_el = el.closest("[data-notification-id]");
  notification_id = data_el.data('notification-id');
  action = el.hasClass('mark-as-read') ? 1 : 0;
  return $.ajax({
    url: "/notifications/" + notification_id + "/markasread",
    type: "PUT",
    data: {
      action: action
    },
    success: function(data) {
      var new_notification_item;
      if (data.status === "success") {
        new_notification_item = $(data.html);
        notification_item.replaceWith(new_notification_item);
        return Rfpez.update_notification_badge(data.unread_count);
      }
    }
  });
});

$(document).on("ready page:load pjax:popstate", function() {
  notifications_loaded = false;
  return $("#notifications-dropdown-trigger").on("click", function() {
    if (notifications_loaded) {
      return;
    }
    return $.ajax({
      url: "/notifications/json",
      dataType: "json",
      success: function(data) {
        var str;
        if (data.status === "success") {
          if (data.count < 1) {
            return $("#notifications-dropdown").removeClass('loading').addClass('none');
          }
          str = "";
          $(data.results).each(function() {
            return str += render_notification(this);
          });
          str += "<li class=\"view-all\"><a href=\"/notifications\">view all " + data.count + " notifications</a></li>";
          $("#notifications-dropdown").removeClass("loading");
          $("#notifications-dropdown").html(str);
          $("#notifications-dropdown span.timeago").timeago();
          return notifications_loaded = true;
        }
      }
    });
  });
});
