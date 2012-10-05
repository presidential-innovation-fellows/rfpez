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

$(document).on "click", ".answer-question-toggle", ->
  el = $(this)
  question = $(this).closest(".question-wrapper")
  form = $("#answer-question-form")
  form.find("input[name=id]").val(question.data('id'))
  form.find("textarea[name=answer]").val('')
  form.appendTo(question)
  form.show()

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

