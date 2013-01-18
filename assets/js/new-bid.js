var update_total_price;

update_total_price = function() {
  var total;
  total = 0;
  $(".deliverable-price").each(function() {
    var price;
    if (price = parseFloat($(this).val())) {
      return total += price;
    }
  });
  $("#total-price").html("$" + total);
  return $(".total-price-input").val(total);
};

$(document).on("click", "#add-deliverable-button", function() {
  return $(".deliverables-row:eq(0)").clone().appendTo(".prices-table tbody").find("input").val("");
});

$(document).on("click", ".remove-deliverable", function() {
  if ($(".deliverables-row").length === 1) {
    $(this).closest('.deliverables-row').find(':input').val('');
  } else {
    $(this).closest(".deliverables-row").remove();
  }
  return update_total_price();
});

$(document).on("input", ".deliverable-price", update_total_price);

$(document).on("ready page:load", function() {
  if ($("#current-page").val() === "new-bid") {
    return update_total_price();
  }
});
