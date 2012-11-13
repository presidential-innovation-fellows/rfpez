$(document).on 'shown', '#dismiss-modal', ->
  $(this).find("select").focus().val('')
  $(this).find("input[name=reason_other]").val('').hide()

$(document).on "change", "#dismiss-modal select", ->
  if $(this).val() is "Other"
    $("#dismiss-modal input[name=reason_other]").show()
  else
    $("#dismiss-modal input[name=reason_other]").val('').hide()

$(document).on "click", "#review-tips-toggle", ->
  $("#review-tips").collapse('toggle')

$(document).on "show", "#review-tips", ->
  $("#review-tips-toggle").data('show-text', $("#review-tips-toggle").text())
  $("#review-tips-toggle").text($("#review-tips-toggle").data('hide-text'))

$(document).on "hide", "#review-tips", ->
  $("#review-tips-toggle").text($("#review-tips-toggle").data('show-text'))

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
        reason_other: modal.find("input[name=reason_other]").val()
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
  vendor_email = data_el.data('vendor-email');
  modal = $("#award-modal")
  modal.find(".company-name").text(vendor_company_name)
  modal.find(".vendor-email").html("""<a href="mailto:#{vendor_email}">#{vendor_email}</a>""")
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


######## HOTKEYS ########

#  Key  Description
#
#   k         move selection up
#   j         move selection down
#   s         star selection
#   o, return open selection
#   d         dismiss selection

on_mouseover_select = true
mouseover_select_timeout = false

# Hack to keep us from "selecting" a new bid when the window is automatically scrolled.
# Suggestions for better solutions welcomed.
keep_bid_in_view = (bid, scrollTo) ->
  on_mouseover_select = false
  clearTimeout(mouseover_select_timeout)

  if scrollTo is "bid"
    bottom = bid.offset().top + bid.height()
    current_bottom = $(window).scrollTop() + $(window).height()

    top = bid.offset().top
    current_top = $(window).scrollTop()

    if (current_bottom < bottom) then $('html, body').scrollTop(bottom - $(window).height())
    if (current_top > top) then $('html, body').scrollTop(bid.offset().top)

  else if scrollTo is "top"
    $('html, body').scrollTop(0)

  mouseover_select_timeout = setTimeout ->
    on_mouseover_select = true
  , 200


Rfpez.select_bid = (bid, scrollTo) ->
  $(".bid").removeClass('selected')
  bid.addClass('selected')
  if scrollTo then keep_bid_in_view(bid, scrollTo)

Rfpez.move_bid_selection = (direction) ->
  selected_bid = $(".bid.selected:eq(0)")
  return if !selected_bid
  all_bids = $(".bid")
  selected_index = all_bids.index(selected_bid)

  if direction is "up"
    if selected_index is 0
      return Rfpez.select_bid(selected_bid, "top")

    new_index = selected_index - 1
  else # direction is "down"
    new_index = selected_index + 1

  new_selection = $(".bid:eq(#{new_index})")
  if new_selection.length > 0 then Rfpez.select_bid(new_selection, "bid")

star_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".star-td .btn:visible").click()

open_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find("a[data-toggle=collapse]").click()

dismiss_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  selected_bid.find(".show-dismiss-modal, .undismiss-button").filter(":visible").click()

toggle_unread_selection = ->
  selected_bid = $(".bid.selected:eq(0)")
  if selected_bid.find(".mark-as-read").is(":visible")
    selected_bid.find(".mark-as-read").click()
  else
    selected_bid.find(".mark-as-unread").click()

$(document).bind 'keydown', 'k', ->
  Rfpez.move_bid_selection("up")

$(document).bind 'keydown', 'j', ->
  Rfpez.move_bid_selection("down")

$(document).bind 'keydown', 's', star_selection
$(document).bind 'keydown', 'return', open_selection
$(document).bind 'keydown', 'o', open_selection
$(document).bind 'keydown', 'd', dismiss_selection
$(document).bind 'keydown', 'u', toggle_unread_selection

$(document).on "mouseover.selectbidmouseover", ".bid", ->
  if Rfpez.current_page("bid-review") and on_mouseover_select
    Rfpez.select_bid($(this), false)
