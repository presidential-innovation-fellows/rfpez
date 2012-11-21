update_vendor_image_preview = ->
  el = $(".vendor-image-url input")
  frame = el.closest(".vendor-image-url").find(".vendor-image-preview-frame")
  hideshow = $(".vendor-image-preview")
  imgval = el.val()
  $("#prev-img-btn").addClass('disabled')
  if imgval == ''
    hideshow.addClass('hide')
  else
    img = frame.find("img")
    img.attr("src", imgval)
    hideshow.removeClass('hide')

vendor_image_keydown = ->
  if event.which == 13
    update_vendor_image_preview()
    event.preventDefault()
    return false
  else
    $("#prev-img-btn").removeClass('disabled')

$(document).on "blur", ".vendor-image-url input", update_vendor_image_preview

$(document).on "keydown", ".vendor-image-url input", vendor_image_keydown

$(document).on "ready pjax:success", ->

  update_vendor_image_preview()