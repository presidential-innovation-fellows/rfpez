var update_vendor_image_preview, vendor_image_keydown;

update_vendor_image_preview = function() {
  var el, frame, hideshow, img, imgval;
  el = $(".vendor-image-url input");
  frame = el.closest(".vendor-image-url").find(".vendor-image-preview-frame");
  hideshow = $(".vendor-image-preview");
  imgval = el.val();
  $("#prev-img-btn").addClass('disabled');
  if (imgval === '') {
    return hideshow.addClass('hide');
  } else {
    img = frame.find("img");
    img.attr("src", imgval);
    return hideshow.removeClass('hide');
  }
};

vendor_image_keydown = function() {
  if (event.which === 13) {
    update_vendor_image_preview();
    event.preventDefault();
    return false;
  } else {
    return $("#prev-img-btn").removeClass('disabled');
  }
};

$(document).on("blur", ".vendor-image-url input", update_vendor_image_preview);

$(document).on("keydown", ".vendor-image-url input", vendor_image_keydown);

$(document).on("ready pjax:success", function() {
  return update_vendor_image_preview();
});
