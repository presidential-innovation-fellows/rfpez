
$(document).on("change keyup", "#filter-projects-input", function() {
  var all_contracts, query;
  query = $.trim($(this).val());
  all_contracts = $(".projects-table .project");
  return $(all_contracts).each(function() {
    if (!query || $(this).text().match(new RegExp(query, 'i'))) {
      return $(this).removeClass('hide');
    } else {
      return $(this).addClass('hide');
    }
  });
});
