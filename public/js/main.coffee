$(document).on "click", "#add-deliverable-button", ->
  $(".deliverables-row:eq(0)").clone().appendTo(".prices-table tbody").find("input").val("")

$(document).on "click", ".remove-deliverable", ->
  $(this).closest(".deliverables-row").remove() unless $(".deliverables-row").length is 1

$(document).on "click", ".show-dismiss-modal", ->
  el = $(this)
  modal = $("#dismiss-modal")
  modal.find(".company-name").text(el.data('vendor-company-name'))
  modal.find("textarea").val("")
  modal.find("button").button('reset')
  modal.modal('show')

  modal.off ".rfpez-dismiss"
  modal.on "click.rfpez-dismiss", ".dismiss-btn", ->
    $(this).button('loading')
    $.ajax
      url: "/contracts/" + el.data('contract-id') + "/bids/" + el.data('bid-id') + "/dismiss"
      data:
        reason: modal.find("select[name=reason]").val()
        explanation: modal.find("textarea[name=explanation]").val()
      type: "GET"
      success: (data) ->
        if data.status is "already dismissed" or "success"
          modal.modal('hide')
          if el.data('remove-from-list')
            el.closest("." + el.data('remove-from-list')).remove()
          else
            window.location.reload()

$(document).on "submit", "#ask-question-form", (e) ->
  e.preventDefault()
  el = $(this)
  button = el.find("button")
  button.button('loading')
  $.ajax
    url: "/questions"
    data:
      contract_id: el.find("input[name=contract_id]").val()
      question: el.find("textarea[name=question]").val()
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

$(document).on "click", ".answer-question-toggle", ->
  el = $(this)
  question = $(this).closest(".question-wrapper")
  form = $("#answer-question-form")
  form.find("input[name=id]").val(question.data('id'))
  form.find("textarea[name=answer]").val('')
  form.appendTo(question)
  form.show()

$(document).on "submit", "#answer-question-form", (e) ->
  e.preventDefault()
  el = $(this)
  question = el.closest(".question-wrapper")
  $.ajax
    url: el.attr('action')
    type: "post"
    data:
      id: el.find("input[name=id]").val()
      answer: el.find("textarea[name=answer]").val()

    success: (data) ->
      if data.status is "success"
        el.hide()
        el.prependTo('body')
        question.find(".answer-question").remove()
        new_question = $(data.html)
        new_question.find(".answer").hide()
        question.replaceWith new_question
        new_question.find(".answer").fadeIn(300)
      else
        alert 'error'
