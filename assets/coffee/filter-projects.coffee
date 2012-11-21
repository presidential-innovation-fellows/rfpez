$(document).on "change keyup", "#filter-projects-input", ->
  query = $.trim($(this).val())
  all_contracts = $(".projects-table .project")

  $(all_contracts).each ->
    if !query or $(this).text().match(new RegExp(query, 'i'))
      $(this).removeClass('hide')
    else
      $(this).addClass('hide')