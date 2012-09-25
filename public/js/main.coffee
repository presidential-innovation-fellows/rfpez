$(document).on "click", "#add-deliverable-button", ->
  $(".deliverables-row:eq(0)").clone().appendTo(".prices-table tbody").find("input").val("")

$(document).on "click", ".remove-deliverable", ->
  $(this).closest(".deliverables-row").remove() unless $(".deliverables-row").length is 1

$(document).on "click", ".show-dismiss-modal", ->
  el = $(this)
  modal = $("#dismiss-modal")
  modal.find(".company-name").text(el.data('vendor-company-name'))
  modal.find("input, textarea").val()
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
          window.location.reload() # cheap hack, in the future this should find the bid in the list and hide it

$ ->
  $("#dismiss-modal").modal
    show: false