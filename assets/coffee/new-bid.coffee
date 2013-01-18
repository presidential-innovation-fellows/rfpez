update_total_price = ->
  total = 0
  $(".deliverable-price").each ->
    if price = parseFloat($(this).val())
      total += price
  $("#total-price").html("$"+total)
  $(".total-price-input").val(total)

$(document).on "click", "#add-deliverable-button", ->
  $(".deliverables-row:eq(0)").clone().appendTo(".prices-table tbody").find("input").val("")

$(document).on "click", ".remove-deliverable", ->
  if $(".deliverables-row").length is 1
    $(this).closest('.deliverables-row').find(':input').val('')
  else
    $(this).closest(".deliverables-row").remove()
  update_total_price()

$(document).on "input", ".deliverable-price", update_total_price

$(document).on "ready page:load", ->
  if $("#current-page").val() is "new-bid"
    update_total_price()