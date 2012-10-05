$(document).on "change keyup", "#filter-contracts-input", ->
  query = $.trim($(this).val())
  all_contracts = $(".contracts-table .contract")

  $(all_contracts).each ->
    if !query or $(this).text().match(new RegExp(query, 'i'))
      $(this).removeClass('hide')
    else
      $(this).addClass('hide')