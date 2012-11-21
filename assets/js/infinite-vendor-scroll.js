var check_for_scroll_position, finished_loading_vendors, load_more_vendors, loading_more_vendors;

loading_more_vendors = false;

finished_loading_vendors = false;

check_for_scroll_position = function() {
  var bottom_of_vendors, current_scroll_position;
  bottom_of_vendors = $('.vendors').offset().top + $('.vendors').height();
  current_scroll_position = $(window).scrollTop() + $(window).height();
  if (current_scroll_position >= (bottom_of_vendors - 500)) {
    return load_more_vendors();
  }
};

load_more_vendors = function() {
  var next_page, vendors_div, vendors_wrapper;
  if (loading_more_vendors === true || finished_loading_vendors === true) {
    return;
  }
  loading_more_vendors = true;
  vendors_div = $('.vendors');
  vendors_wrapper = $('.vendors-wrapper');
  vendors_wrapper.addClass('loading');
  next_page = (vendors_wrapper.data('current-page') || 1) + 1;
  return $.ajax({
    url: "/vendors?page=" + next_page,
    success: function(data) {
      var new_vendors;
      new_vendors = $(data).find(".vendors .vendor");
      vendors_wrapper.removeClass('loading');
      loading_more_vendors = false;
      vendors_wrapper.data('current-page', next_page);
      vendors_div.append(new_vendors);
      if (new_vendors.length <= 9) {
        finished_loading_vendors = true;
        return vendors_wrapper.addClass('finished-loading');
      }
    }
  });
};

$(document).on('ready scroll', function() {
  if (Rfpez.current_page("vendors-index")) {
    return check_for_scroll_position();
  }
});
