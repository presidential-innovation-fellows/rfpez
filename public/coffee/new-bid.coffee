$(document).on "click", "#add-deliverable-button", ->
  $(".deliverables-row:eq(0)").clone().appendTo(".prices-table tbody").find("input").val("")

$(document).on "click", ".remove-deliverable", ->
  if $(".deliverables-row").length is 1
    $(this).closest('.deliverables-row').find(':input').val('')
  else
    $(this).closest(".deliverables-row").remove()
