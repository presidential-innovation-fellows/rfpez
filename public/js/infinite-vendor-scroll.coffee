loading_more_vendors = false
finished_loading_vendors = false

check_for_scroll_position = ->
  vendors_div = $('.vendors')
  bottom_of_vendors = vendors_div.offset().top + vendors_div.height()
  current_scroll_position = $(document).scrollTop() + $(window).height()
  load_more_vendors() if current_scroll_position >= (bottom_of_vendors - 500)

load_more_vendors = ->
  return if loading_more_vendors is true or finished_loading_vendors is true
  loading_more_vendors = true
  vendors_div = $('.vendors')
  vendors_wrapper = $('.vendors-wrapper')
  vendors_wrapper.addClass('loading')
  current_page = vendors_wrapper.data('current-page') || 1
  next_page = current_page + 1
  $.ajax
    url: "/vendors?page=#{next_page}"
    success: (data) ->
      new_vendors = $(data).find(".vendors")
      vendors_wrapper.removeClass('loading')

      if new_vendors.children().length is 0
        finished_loading_vendors = true
        loading_more_vendors = false
        vendors_wrapper.addClass('finished-loading')
      else
        vendors_div.append(new_vendors)
        loading_more_vendors = false
        vendors_wrapper.data('current-page', next_page)

$(document).on 'scroll', check_for_scroll_position

$ ->
  check_for_scroll_position