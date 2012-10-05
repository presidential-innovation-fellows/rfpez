loading_more_vendors = false
finished_loading_vendors = false

check_for_scroll_position = ->
  bottom_of_vendors = $('.vendors').offset().top + $('.vendors').height()
  current_scroll_position = $(window).scrollTop() + $(window).height()
  load_more_vendors() if current_scroll_position >= (bottom_of_vendors - 500)

load_more_vendors = ->
  return if loading_more_vendors is true or finished_loading_vendors is true

  loading_more_vendors = true
  vendors_div = $('.vendors')
  vendors_wrapper = $('.vendors-wrapper')
  vendors_wrapper.addClass('loading')
  next_page = (vendors_wrapper.data('current-page') || 1) + 1

  $.ajax
    url: "/vendors?page=#{next_page}"
    success: (data) ->
      new_vendors = $(data).find(".vendors .vendor")
      vendors_wrapper.removeClass('loading')
      loading_more_vendors = false
      vendors_wrapper.data('current-page', next_page)
      vendors_div.append(new_vendors)

      if new_vendors.length <= 9
        finished_loading_vendors = true
        vendors_wrapper.addClass('finished-loading')


$(document).on 'ready scroll', check_for_scroll_position