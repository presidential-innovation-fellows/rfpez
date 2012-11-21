
$(document).on("submit", "#answer-question-form", function(e) {
  var answer_text, el, question, question_id;
  e.preventDefault();
  el = $(this);
  answer_text = el.find("textarea[name=answer]").val();
  if (!answer_text) {
    return;
  }
  el.find("button").button('loading');
  question = el.closest(".question-wrapper");
  question_id = el.closest("[data-question-id]").data('question-id');
  return el.ajaxSubmit({
    url: "/questions/" + question_id,
    success: function(data) {
      var new_question;
      if (data.status === "success") {
        el.hide();
        el.find("button").button('reset');
        el.prependTo('body');
        question.find(".answer-question").remove();
        new_question = $(data.html);
        new_question.find(".answer").hide();
        question.replaceWith(new_question);
        return new_question.find(".answer").fadeIn(300);
      } else {
        return el.find('button').button('reset').flash_button_message("danger", "Error occurred");
      }
    }
  });
});

$(document).on("click", ".answer-question-toggle", function() {
  var el, form, question;
  el = $(this);
  question = $(this).closest(".question-wrapper");
  form = $("#answer-question-form");
  form.find("input[name=id]").val(question.data('id'));
  form.find("textarea[name=answer]").val('');
  form.appendTo(question);
  return form.show();
});

$(document).on("submit", "#ask-question-form", function(e) {
  var button, el, question_text;
  e.preventDefault();
  el = $(this);
  question_text = el.find("textarea[name=question]").val();
  if (!question_text) {
    return;
  }
  button = el.find("button");
  button.button('loading');
  return $.ajax({
    url: "/questions",
    data: {
      project_id: el.find("input[name=project_id]").val(),
      question: question_text
    },
    type: "POST",
    success: function(data) {
      var new_question;
      button.button('reset');
      el.find("textarea[name=question]").val('');
      if (data.status === "success") {
        $("p.no-questions-asked").hide();
        new_question = $(data.html);
        new_question.hide();
        $(".questions").append(new_question);
        return new_question.fadeIn(300);
      } else {
        return alert('error!');
      }
    }
  });
});
