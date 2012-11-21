(function($){

  $.fn.formTimer = function(options) {

    var settings = $.extend({
      'url' : false
    }, options);

    return this.each(function(){

      var form = $(this);
      var startTime = false;
      var endTime = false;
      var timerSent = false;
      var duration;
      var formId;

      formId = form.data('formtimer-name') || form.attr('id') || form.attr('name') || form.attr('action');

      if (!formId) return;

      form.find(':input').on('focus', function(e){
        if (!startTime) startTime = new Date();
      });

      form.on('submit', function(e){
        if (timerSent) return;

        e.preventDefault();
        if (!startTime) startTime = new Date();
        endTime = new Date();
        duration = endTime.getTime() - startTime.getTime();

        timerSent = true;

        var data = {formId: formId, startTime: startTime, endTime: endTime, duration: duration};

        if (typeof _gaq !== "undefined") _gaq.push(['_trackEvent', 'FormTimer', 'Form Submitted', formId, duration]);

        if (settings.url) {
          $.getJSON(settings.url, data).complete(function(){
            form.submit();
          });
        } else {
          form.submit();
        }

      });

    });

  };

})(jQuery);