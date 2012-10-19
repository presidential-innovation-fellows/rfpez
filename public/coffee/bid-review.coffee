$(document).on 'shown', '#dismiss-modal', ->
  $(this).find("select").focus()

$(document).on "click", ".bid-notification-td .mark-as-read, .bid-notification-td .mark-as-unread", ->
  el = $(this)
  bid = el.closest(".bid")
  bid_id = bid.data('bid-id')
  action = if el.hasClass('mark-as-read') then "read" else "unread"

  Rfpez.view_notification_payload("bid", bid_id, action)

  if action is "read"
    bid.removeClass('unread')
  else
    bid.addClass('unread')

$(document).on "click", ".bid .unstar-button, .bid .star-button", ->
  action = if $(this).hasClass('unstar-button') then "0" else "1"
  bid = $(this).closest(".bid")
  $.ajax
    url: "/projects/" + bid.data('project-id') + "/bids/" + bid.data('bid-id') + "/star"
    data:
      starred: action
    type: "GET"
    success: (data) ->
      if data.starred is '0'
        bid.find(".star-td").removeClass("starred")
      else
        bid.find(".star-td").addClass("starred")

$(document).on 'show', '.bid-details .collapse', ->
  bid = $(this).closest(".bid")
  bid_id = bid.data('bid-id')
  $(this).find(".dsbs-certifications").trigger('load-dsbs')
  bid.removeClass('unread')
  Rfpez.view_notification_payload('bid', bid_id, "read")

$(document).on "click", ".undismiss-button", ->
  el = $(this)
  bid = el.closest(".bid")
  data_el = el.closest("[data-bid-id]")
  project_id = data_el.data('project-id')
  bid_id = data_el.data('bid-id')

  $.ajax
    url: "/projects/#{project_id}/bids/#{bid_id}/dismiss"
    type: "GET"
    success: (data) ->
      if data.status is "success"
        if el.data('move-to-table')
          Rfpez.move_bid_selection("down")
          new_bid = $(data.html)
          bid.remove()
          $(".bids-table.open-bids > thead").after(new_bid)
        else
          window.location.reload()

$(document).on "click", ".show-dismiss-modal", ->
  el = $(this)
  bid = el.closest(".bid")
  data_el = el.closest("[data-bid-id]")
  project_id = data_el.data('project-id')
  bid_id = data_el.data('bid-id')
  vendor_company_name = data_el.data('vendor-company-name');
  modal = $("#dismiss-modal")
  modal.find(".company-name").text(vendor_company_name)
  modal.find("textarea").val("")
  modal.find(".dismiss-btn").button('reset')
  modal.modal('show')

  modal.off ".rfpez-dismiss"
  modal.on "submit.rfpez-dismiss", "form", (e) ->
    e.preventDefault()
    $(this).find(".dismiss-btn").button('loading')
    $.ajax
      url: "/projects/#{project_id}/bids/#{bid_id}/dismiss"
      data:
        reason: modal.find("select[name=reason]").val()
        explanation: modal.find("textarea[name=explanation]").val()
      type: "GET"
      dataType: "json"
      success: (data) ->
        if data.status is "already dismissed" or "success"
          modal.modal('hide')
          if el.data('move-to-table')
            Rfpez.move_bid_selection("down")
            bid.remove()
            new_bid = $(data.html)
            $(".bids-table.dismissed-bids > thead").after(new_bid)
          else
            window.location.reload()

$(document).on "click", ".show-award-modal", ->
  el = $(this)
  bid = el.closest(".bid")
  data_el = el.closest("[data-bid-id]")
  project_id = data_el.data('project-id')
  bid_id = data_el.data('bid-id')
  vendor_company_name = data_el.data('vendor-company-name');
  modal = $("#award-modal")
  modal.find(".company-name").text(vendor_company_name)
  modal.find(".award-btn").button('reset')
  modal.modal('show')

  modal.off ".rfpez-award"
  modal.on "submit.rfpez-award", "form", (e) ->
    e.preventDefault()
    $(this).find(".award-btn").button('loading')
    $.ajax
      url: "/projects/#{project_id}/bids/#{bid_id}/award"
      data:
        awarded_message: modal.find("textarea[name=awarded_message]").val()
      type: "GET"
      dataType: "json"
      success: (data) ->
        if data.status is "success"
          modal.modal('hide')
          if el.data('move-to-table')
            bid.remove()
            new_bid = $(data.html)
            $(".bids-table.winning-bid > thead").after(new_bid)
            $(".winning-bid-table-wrapper").removeClass('hide')
          else
            window.location.reload()

$(document).on "click", ".manual-awarded-message-checkbox", ->
  el = $(this)
  modal = $("#award-modal")
  awarded_message = modal.find(".awarded-message")
  if el.is(":checked")
    awarded_message.data('original-val', awarded_message.val())
                   .val("")
                   .attr('disabled', true)
  else
    awarded_message.val(awarded_message.data('original-val'))
                   .removeAttr('disabled')
