$ ->
  $('.datepicker').datepicker
    autoclose: true

  $(".form-sections input.section-toggle:checked").each ->
    $(this).closest(".control-group")
           .addClass("checked")
           .find("input[disabled]")
           .removeAttr('disabled');

  $(".sow-content p input[type=text]").autoGrow
    comfortZone: 5

  $(".checkbox-tooltip").tooltip
      placement: 'top'

  if $("#sow-content-wysiwyg").length > 0
    $("#sow-content-wysiwyg").wysihtml5
      parserRules: wysihtml5ParserRules
      stylesheets: ["/css/bootstrap.css", "/css/wysihtml5.css"]

$(document).on "click", ".examples-toggle", ->
  if $(".examples").is(":visible")
    $(this).text($(this).data('show-text'))
  else
    $(this).text($(this).data('hide-text'))
  $(".examples").slideToggle(250)

$(document).on "focus", ".sow-content p input[type=text]", ->
  el = $(this)
  el.tooltip
    title: el.data('helper-text')
    placement: 'bottom'
    trigger: 'manual'
  el.tooltip('show')

$(document).on "keydown", ".sow-content p input[type=text]", (e) ->
  if (e.keyCode == 13 || e.keyCode == 9)
    inputs = $("input[data-variable]")
    index = inputs.index(this) + 1

    while index < inputs.length
      input = $(inputs[index])
      if input.val() == ""
        e.preventDefault()
        return input.select()
      index++

$(document).on "blur", ".sow-content p input[type=text]", ->
  $(this).tooltip('hide')

$(document).on "input blur", ".sow-content p input[type=text]", (e) ->
  el = $(this)
  variableName = el.data('variable')
  variableValue = el.val()
  $(".sow-content p input[data-variable=#{variableName}]").each ->
    $(this).val(variableValue)
    $(this).trigger("input.autogrow")

$(document).on "click", ".form-sections input.section-toggle", ->
  if this.checked
    $(this).closest(".control-group")
           .addClass("checked")
           .find("input[disabled]")
           .removeAttr('disabled')
  else
    $(this).closest(".control-group")
           .removeClass("checked")
           .find("input[name*=deliverable_dates]")
           .attr('disabled', '')

$(document).on "click", "#add-custom-choice-btn", (e) ->
  e.preventDefault()
  text = $("#add-custom-choice-text").val()
  return if !text
  $("#add-custom-choice-text").val('')

  newChoice = $(".custom-choices .template").clone()
  newChoice.find("input[disabled]").removeAttr('disabled');
  newChoice.appendTo(".custom-choices")
  newChoice.find(".choice-name span").html(text)
  newChoice.find(".choice-name input[name=custom_sections\\[\\]]").val(text)
  newChoice.removeClass("template")
  newChoice.find(".datepicker").datepicker
    autoclose: true

$(document).on "click", ".custom-choices li a", ->
  $(this).closest("li").remove()

$(document).on "click", ".select-on-click", ->
  $(this).select()