var dismiss_selection, keep_bid_in_view, mouseover_select_timeout, on_mouseover_select, open_selection, star_selection, toggle_unread_selection;

$(document).on('shown', '#dismiss-modal', function() {
  $(this).find("select").focus().val('');
  return $(this).find("input[name=reason_other]").val('').hide();
});

$(document).on("change", "#dismiss-modal select", function() {
  if ($(this).val() === "Other") {
    return $("#dismiss-modal input[name=reason_other]").show();
  } else {
    return $("#dismiss-modal input[name=reason_other]").val('').hide();
  }
});

$(document).on("click", "#review-tips-toggle", function() {
  return $("#review-tips").collapse('toggle');
});

$(document).on("show", "#review-tips", function() {
  $("#review-tips-toggle").data('show-text', $("#review-tips-toggle").text());
  return $("#review-tips-toggle").text($("#review-tips-toggle").data('hide-text'));
});

$(document).on("hide", "#review-tips", function() {
  return $("#review-tips-toggle").text($("#review-tips-toggle").data('show-text'));
});

$(document).on("click", ".bid-notification-td .mark-as-read, .bid-notification-td .mark-as-unread", function() {
  var action, bid, bid_id, el;
  el = $(this);
  bid = el.closest(".bid");
  bid_id = bid.data('bid-id');
  action = el.hasClass('mark-as-read') ? "read" : "unread";
  Rfpez.view_notification_payload("bid", bid_id, action);
  if (action === "read") {
    return bid.removeClass('unread');
  } else {
    return bid.addClass('unread');
  }
});

$(document).on("click", ".bid .unstar-button, .bid .star-button", function() {
  var action, bid;
  action = $(this).hasClass('unstar-button') ? "0" : "1";
  bid = $(this).closest(".bid");
  return $.ajax({
    url: "/projects/" + bid.data('project-id') + "/bids/" + bid.data('bid-id') + "/star",
    data: {
      starred: action
    },
    type: "GET",
    success: function(data) {
      if (data.starred === '0') {
        return bid.find(".star-td").removeClass("starred");
      } else {
        return bid.find(".star-td").addClass("starred");
      }
    }
  });
});

$(document).on('show', '.bid-details .collapse', function() {
  var bid, bid_id;
  bid = $(this).closest(".bid");
  bid_id = bid.data('bid-id');
  $(this).find(".dsbs-certifications").trigger('load-dsbs');
  bid.removeClass('unread');
  return Rfpez.view_notification_payload('bid', bid_id, "read");
});

$(document).on("click", ".undismiss-button", function() {
  var bid, bid_id, data_el, el, project_id;
  el = $(this);
  bid = el.closest(".bid");
  data_el = el.closest("[data-bid-id]");
  project_id = data_el.data('project-id');
  bid_id = data_el.data('bid-id');
  return $.ajax({
    url: "/projects/" + project_id + "/bids/" + bid_id + "/dismiss",
    type: "GET",
    success: function(data) {
      var new_bid;
      if (data.status === "success") {
        if (el.data('move-to-table')) {
          Rfpez.move_bid_selection("down");
          new_bid = $(data.html);
          bid.remove();
          return $(".bids-table.open-bids > thead").after(new_bid);
        } else {
          return window.location.reload();
        }
      }
    }
  });
});

$(document).on("click", ".show-dismiss-modal", function() {
  var bid, bid_id, data_el, el, modal, project_id, vendor_company_name;
  el = $(this);
  bid = el.closest(".bid");
  data_el = el.closest("[data-bid-id]");
  project_id = data_el.data('project-id');
  bid_id = data_el.data('bid-id');
  vendor_company_name = data_el.data('vendor-company-name');
  modal = $("#dismiss-modal");
  modal.find(".company-name").text(vendor_company_name);
  modal.find("textarea").val("");
  modal.find(".dismiss-btn").button('reset');
  modal.modal('show');
  modal.off(".rfpez-dismiss");
  return modal.on("submit.rfpez-dismiss", "form", function(e) {
    e.preventDefault();
    $(this).find(".dismiss-btn").button('loading');
    return $.ajax({
      url: "/projects/" + project_id + "/bids/" + bid_id + "/dismiss",
      data: {
        reason: modal.find("select[name=reason]").val(),
        reason_other: modal.find("input[name=reason_other]").val(),
        explanation: modal.find("textarea[name=explanation]").val()
      },
      type: "GET",
      dataType: "json",
      success: function(data) {
        var new_bid;
        if (data.status === "already dismissed" || "success") {
          modal.modal('hide');
          if (el.data('move-to-table')) {
            Rfpez.move_bid_selection("down");
            bid.remove();
            new_bid = $(data.html);
            return $(".bids-table.dismissed-bids > thead").after(new_bid);
          } else {
            return window.location.reload();
          }
        }
      }
    });
  });
});

$(document).on("click", ".show-award-modal", function() {
  var bid, bid_id, data_el, el, modal, project_id, vendor_company_name, vendor_email;
  el = $(this);
  bid = el.closest(".bid");
  data_el = el.closest("[data-bid-id]");
  project_id = data_el.data('project-id');
  bid_id = data_el.data('bid-id');
  vendor_company_name = data_el.data('vendor-company-name');
  vendor_email = data_el.data('vendor-email');
  modal = $("#award-modal");
  modal.find(".company-name").text(vendor_company_name);
  modal.find(".vendor-email").html("<a href=\"mailto:" + vendor_email + "\">" + vendor_email + "</a>");
  modal.find(".award-btn").button('reset');
  modal.modal('show');
  modal.off(".rfpez-award");
  return modal.on("submit.rfpez-award", "form", function(e) {
    e.preventDefault();
    $(this).find(".award-btn").button('loading');
    return $.ajax({
      url: "/projects/" + project_id + "/bids/" + bid_id + "/award",
      data: {
        awarded_message: modal.find("textarea[name=awarded_message]").val()
      },
      type: "GET",
      dataType: "json",
      success: function(data) {
        if (data.status === "success") {
          modal.modal('hide');
          return window.location.reload();
        }
      }
    });
  });
});

$(document).on("click", ".manual-awarded-message-checkbox", function() {
  var awarded_message, el, modal;
  el = $(this);
  modal = $("#award-modal");
  awarded_message = modal.find(".awarded-message");
  if (el.is(":checked")) {
    return awarded_message.data('original-val', awarded_message.val()).val("").attr('disabled', true);
  } else {
    return awarded_message.val(awarded_message.data('original-val')).removeAttr('disabled');
  }
});

on_mouseover_select = true;

mouseover_select_timeout = false;

keep_bid_in_view = function(bid, scrollTo) {
  var bottom, current_bottom, current_top, top;
  on_mouseover_select = false;
  clearTimeout(mouseover_select_timeout);
  if (scrollTo === "bid") {
    bottom = bid.offset().top + bid.height();
    current_bottom = $(window).scrollTop() + $(window).height();
    top = bid.offset().top;
    current_top = $(window).scrollTop();
    if (current_bottom < bottom) {
      $('html, body').scrollTop(bottom - $(window).height());
    }
    if (current_top > top) {
      $('html, body').scrollTop(bid.offset().top);
    }
  } else if (scrollTo === "top") {
    $('html, body').scrollTop(0);
  }
  return mouseover_select_timeout = setTimeout(function() {
    return on_mouseover_select = true;
  }, 200);
};

Rfpez.select_bid = function(bid, scrollTo) {
  $(".bid").removeClass('selected');
  bid.addClass('selected');
  if (scrollTo) {
    return keep_bid_in_view(bid, scrollTo);
  }
};

Rfpez.move_bid_selection = function(direction) {
  var all_bids, new_index, new_selection, selected_bid, selected_index;
  selected_bid = $(".bid.selected:eq(0)");
  if (!selected_bid) {
    return;
  }
  all_bids = $(".bid");
  selected_index = all_bids.index(selected_bid);
  if (direction === "up") {
    if (selected_index === 0) {
      return Rfpez.select_bid(selected_bid, "top");
    }
    new_index = selected_index - 1;
  } else {
    new_index = selected_index + 1;
  }
  new_selection = $(".bid:eq(" + new_index + ")");
  if (new_selection.length > 0) {
    return Rfpez.select_bid(new_selection, "bid");
  }
};

star_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".star-td .btn:visible").click();
};

open_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find("a[data-toggle=collapse]").click();
};

dismiss_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  return selected_bid.find(".show-dismiss-modal, .undismiss-button").filter(":visible").click();
};

toggle_unread_selection = function() {
  var selected_bid;
  selected_bid = $(".bid.selected:eq(0)");
  if (selected_bid.find(".mark-as-read").is(":visible")) {
    return selected_bid.find(".mark-as-read").click();
  } else {
    return selected_bid.find(".mark-as-unread").click();
  }
};

$(document).bind('keydown', 'k', function() {
  return Rfpez.move_bid_selection("up");
});

$(document).bind('keydown', 'j', function() {
  return Rfpez.move_bid_selection("down");
});

$(document).bind('keydown', 's', star_selection);

$(document).bind('keydown', 'return', open_selection);

$(document).bind('keydown', 'o', open_selection);

$(document).bind('keydown', 'd', dismiss_selection);

$(document).bind('keydown', 'u', toggle_unread_selection);

$(document).on("mouseover.selectbidmouseover", ".bid", function() {
  if (Rfpez.current_page("bid-review") && on_mouseover_select) {
    return Rfpez.select_bid($(this), false);
  }
});
