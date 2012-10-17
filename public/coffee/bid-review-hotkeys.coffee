#  Key  Description
#
#   i   move selection up
#   k   move selection down


# Hack to keep us from "selecting" a new bid when the window is automatically scrolled.
# Suggestions for better solutions welcomed.
keep_bid_in_view = (bid, scrollTo) ->
  $(document).off "mouseover.selectbidmouseover"

  if scrollTo is "bid"
    bottom = bid.offset().top + bid.height()
    current_bottom = $(window).scrollTop() + $(window).height()

    top = bid.offset().top
    current_top = $(window).scrollTop()

    if (current_bottom < bottom) then $('html, body').scrollTop(bottom - $(window).height())
    if (current_top > top) then $('html, body').scrollTop(bid.offset().top)

  else if scrollTo is "top"
    $('html, body').scrollTop(0)

  setTimeout ->
    $(document).on "mouseover.selectbidmouseover", ".bid", select_this_bid
  , 200


select_bid = (bid, scrollTo) ->
  scrollTo ||= "bid"
  $(".bid").removeClass('selected')
  bid.addClass('selected')
  keep_bid_in_view(bid, scrollTo)



select_this_bid = ->
  select_bid($(this))

move_selection = (direction) ->
  selected_bid = $(".bid.selected:eq(0)")
  return if !selected_bid
  all_bids = $(".bid")
  selected_index = all_bids.index(selected_bid)

  if direction is "up"
    if selected_index is 0
      return select_bid(selected_bid, "top")

    new_index = selected_index - 1
  else # direction is "down"
    new_index = selected_index + 1

  new_selection = $(".bid:eq(#{new_index})")
  if new_selection.length > 0 then select_bid(new_selection)

$(document).bind 'keydown', 'i', ->
  move_selection("up")

$(document).bind 'keydown', 'k', ->
  move_selection("down")

$(document).on "mouseover.selectbidmouseover", ".bid", select_this_bid