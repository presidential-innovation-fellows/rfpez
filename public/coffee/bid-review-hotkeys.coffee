#  Key  Description
#
#   i       move selection up
#   k       move selection down
#   s       star selection
#   return  open selection
#   d       dismiss selection

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
  selected_bid.find(".show-dismiss-modal").click()

$(document).bind 'keydown', 'i', ->
  Rfpez.move_bid_selection("up")

$(document).bind 'keydown', 'k', ->
  Rfpez.move_bid_selection("down")

$(document).bind 'keydown', 's', star_selection
$(document).bind 'keydown', 'return', open_selection
$(document).bind 'keydown', 'd', dismiss_selection

$(document).on "mouseover.selectbidmouseover", ".bid", ->
  if on_mouseover_select then Rfpez.select_bid($(this), false)