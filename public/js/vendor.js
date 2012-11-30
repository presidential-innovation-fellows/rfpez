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
  return $("#total-price").html("$" + total);
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


$(document).on("ready page:load", function() {
  var draft_saved, form_update_handler, save_draft, save_draft_button;
  if (!Rfpez.current_page("new-bid")) {
    return;
  }
  draft_saved = true;
  save_draft_button = $("#save-draft-button");
  save_draft_button.button('loading');
  form_update_handler = function() {
    draft_saved = false;
    return save_draft_button.button('reset');
  };
  save_draft = function() {
    var form;
    if (draft_saved === true) {
      return;
    }
    form = $(".new-bid-form");
    form.find("input[name=submit_now]").val('false');
    form.ajaxSubmit();
    form.find("input[name=submit_now]").val('true');
    draft_saved = true;
    return save_draft_button.button('loading');
  };
  $("#save-draft-button").on("click", save_draft);
  $(".new-bid-form :input").on("input", form_update_handler);
  $("#add-deliverable-button, .remove-deliverable").on("click", form_update_handler);
  return window.setInterval(save_draft, 5000);
});
