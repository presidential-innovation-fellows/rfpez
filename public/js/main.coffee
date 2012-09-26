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
  el = $(this)
  button = el.find("button")
  button.button('loading')
  e.preventDefault()
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
        $(".questions").append """
          <div class="question-wrapper">
            <div class="question">#{data.question.question}</div>
            <div class="answer"></div>
          </div>
        """
      else
        alert 'error!'

$ ->
  $("#dismiss-modal").modal
    show: false