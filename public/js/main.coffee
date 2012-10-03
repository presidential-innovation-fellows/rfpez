update_vendor_image_preview = ->
  el = $(".vendor-image-url input")
  frame = el.closest(".vendor-image-url").find(".vendor-image-preview-frame")
  hideshow = $(".vendor-image-preview")
  imgval = el.val()
  $("#prev-img-btn").removeClass('btn-primary')
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
    $("#prev-img-btn").addClass('btn-primary')

$(document).on "click", ".bid .unstar-button, .bid .star-button", ->
  action = if $(this).hasClass('unstar-button') then "0" else "1"
  bid = $(this).closest(".bid")
  $.ajax
    url: "/contracts/" + bid.data('contract-id') + "/bids/" + bid.data('bid-id') + "/star"
    data:
      starred: action
    type: "GET"
    success: (data) ->
      if data.starred is '0'
        bid.find(".star-td").removeClass("starred")
      else
        bid.find(".star-td").addClass("starred")

$(document).on "click", "a[data-confirm]", (e) ->
  e.preventDefault();
  el = $(this)
  if confirm(el.data('confirm'))
    window.location = el.attr('href')

$(document).on "click", "#add-deliverable-button", ->
  $(".deliverables-row:eq(0)").clone().appendTo(".prices-table tbody").find("input").val("")

$(document).on "click", ".remove-deliverable", ->
  if $(".deliverables-row").length is 1
    $(this).closest('.deliverables-row').find(':input').val('')
  else
    $(this).closest(".deliverables-row").remove()

$(document).on "click", ".undismiss-button", ->
  el = $(this)
  bid = el.closest(".bid")
  data_el = el.closest("[data-bid-id]")
  contract_id = data_el.data('contract-id')
  bid_id = data_el.data('bid-id')

  $.ajax
    url: "/contracts/#{contract_id}/bids/#{bid_id}/dismiss"
    type: "GET"
    success: (data) ->
      if data.status is "success"
        if el.data('move-to-table')
          new_bid = $(data.html)
          bid.remove()
          $(".bids-table.open-bids > thead").after(new_bid)
        else
          window.location.reload()

$(document).on "click", ".show-dismiss-modal", ->
  el = $(this)
  bid = el.closest(".bid")
  data_el = el.closest("[data-bid-id]")
  contract_id = data_el.data('contract-id')
  bid_id = data_el.data('bid-id')
  vendor_company_name = data_el.data('vendor-company-name');
  modal = $("#dismiss-modal")
  modal.find(".company-name").text(vendor_company_name)
  modal.find("textarea").val("")
  modal.find(".dismiss-btn").button('reset')
  modal.modal('show')

  modal.off ".rfpez-dismiss"
  modal.on "submit.rfpez-dismiss", "form", (e) ->
    e.preventDefault()
    $(this).find(".dismiss-btn").button('loading')
    $.ajax
      url: "/contracts/#{contract_id}/bids/#{bid_id}/dismiss"
      data:
        reason: modal.find("select[name=reason]").val()
        explanation: modal.find("textarea[name=explanation]").val()
      type: "GET"
      success: (data) ->
        if data.status is "already dismissed" or "success"
          modal.modal('hide')
          if el.data('move-to-table')
            bid.remove()
            new_bid = $(data.html)
            $(".bids-table.dismissed-bids > thead").after(new_bid)
          else
            window.location.reload()

$(document).on "submit", "#ask-question-form", (e) ->
  e.preventDefault()
  el = $(this)
  question_text = el.find("textarea[name=question]").val()
  return if !question_text
  button = el.find("button")
  button.button('loading')
  $.ajax
    url: "/questions"
    data:
      contract_id: el.find("input[name=contract_id]").val()
      question: question_text
    type: "POST"
    success: (data) ->
      button.button('reset')
      el.find("textarea[name=question]").val('')
      if data.status is "success"
        new_question = $(data.html)
        new_question.hide()
        $(".questions").append new_question
        new_question.fadeIn(300)
      else
        alert 'error!'

$(document).on "blur", ".vendor-image-url input", update_vendor_image_preview

$(document).on "keydown", ".vendor-image-url input", vendor_image_keydown

$(document).on "click", ".answer-question-toggle", ->
  el = $(this)
  question = $(this).closest(".question-wrapper")
  form = $("#answer-question-form")
  form.find("input[name=id]").val(question.data('id'))
  form.find("textarea[name=answer]").val('')
  form.appendTo(question)
  form.show()

$(document).on "submit", "#new-contract-form", (e) ->
  return e.preventDefault() unless $(this).find('input[name=solnbr]').val()
  $(this).find("button[type=submit]").button('loading')

$(document).on "submit", "#answer-question-form", (e) ->
  e.preventDefault()
  el = $(this)
  answer_text = el.find("textarea[name=answer]").val()
  return unless answer_text
  el.find("button").button('loading')
  question = el.closest(".question-wrapper")
  $.ajax
    url: el.attr('action')
    type: "post"
    data:
      id: el.find("input[name=id]").val()
      answer: answer_text

    success: (data) ->
      if data.status is "success"
        el.hide()
        el.find("button").button('reset')
        el.prependTo('body')
        question.find(".answer-question").remove()
        new_question = $(data.html)
        new_question.find(".answer").hide()
        question.replaceWith new_question
        new_question.find(".answer").fadeIn(300)
      else
        alert 'error'

$ ->
  update_vendor_image_preview()
