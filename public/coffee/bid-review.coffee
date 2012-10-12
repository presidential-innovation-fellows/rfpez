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
  data_el = $(this).closest("[data-bid-id]")
  bid_id = data_el.data('bid-id')
  Rfpez.view_notification_payload('bid', bid_id)

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
      success: (data) ->
        if data.status is "already dismissed" or "success"
          modal.modal('hide')
          if el.data('move-to-table')
            bid.remove()
            new_bid = $(data.html)
            $(".bids-table.dismissed-bids > thead").after(new_bid)
          else
            window.location.reload()

