`if (!Array.prototype.indexOf) {
  Array.prototype.indexOf = function (searchElement /*, fromIndex */ ) {
    "use strict";
    if (this == null) {
      throw new TypeError();
    }
    var t = Object(this);
    var len = t.length >>> 0;
    if (len === 0) {
      return -1;
    }
    var n = 0;
    if (arguments.length > 1) {
      n = Number(arguments[1]);
      if (n != n) { // shortcut for verifying if it's NaN
        n = 0;
      } else if (n != 0 && n != Infinity && n != -Infinity) {
        n = (n > 0 || -1) * Math.floor(Math.abs(n));
      }
    }
    if (n >= len) {
      return -1;
    }
    var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
    for (; k < len; k++) {
      if (k in t && t[k] === searchElement) {
        return k;
      }
    }
    return -1;
  }
}`

Date.prototype.toISOString = Date.prototype.toISOString || ->

  return this.getUTCFullYear() + "-"
  + ("0" + this.getUTCMonth() + 1 + "-").slice(-3)
  + ("0" + this.getUTCDate() + "T").slice(-3)
  + ("0" + this.getUTCHours() + ":").slice(-3)
  + ("0" + this.getUTCMinutes() + ":").slice(-3)
  + ("0" + this.getUTCSeconds() + ".").slice(-3)
  + ("00" + this.getUTCMilliseconds() + "Z").slice(-4)

window.Rfpez ||= {
  Backbone: {}
}

Rfpez.current_page = (str) ->
  if str is Rfpez.current_page_string
    true
  else
    false

$(document).on 'shown', '#signinModal', ->
  $("#signinModal #email").focus()

$(document).on "click", "a[data-confirm]", (e) ->
  e.preventDefault();
  el = $(this)
  if confirm(el.data('confirm'))
    window.location = el.attr('href')

$(document).on "submit", "#new-contract-form", (e) ->
  return e.preventDefault() unless $(this).find('input[name=solnbr]').val()
  $(this).find("button[type=submit]").button('loading')

$(document).on "click", "[data-select-text-on-focus]", (e) ->
  $(this).select()

$(document).on "mouseenter", ".helper-tooltip", (e) ->
  $(this).tooltip()
  $(this).tooltip('show')

$(document).on "mouseleave", ".helper-tooltip", (e) ->
  $(this).tooltip('hide')

$(document).on "ready page:load", ->
  $("[data-onload-focus]:eq(0)").focus()
  $("span.timeago").timeago()
  $('input, textarea').placeholder()

  if $("body").hasClass('officer')
    $('.datepicker-wrapper').datepicker()
    $('.wysihtml5').wysihtml5()

  Rfpez.current_page_string = $("body").data('current-page')
