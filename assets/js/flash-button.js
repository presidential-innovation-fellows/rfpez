var $;

$ = jQuery;

$.fn.extend({
  flash_button_message: function(button_class, message, timeout) {
    timeout || (timeout = 1000);
    return this.each(function() {
      var button, original_classes, original_text;
      button = $(this);
      original_text = button.text();
      original_classes = button.attr('class');
      button.removeClass('btn-primary btn-info btn-success btn-warning btn-danger btn-inverse btn-link');
      button.addClass("btn-" + button_class);
      button.text(message);
      return setTimeout(function() {
        button.attr('class', original_classes);
        button.removeClass('disabled');
        return button.text(original_text);
      }, timeout);
    });
  }
});
