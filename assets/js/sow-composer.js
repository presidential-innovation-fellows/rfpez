var add_empty_class_to_inputs, apply_section_cover, available_sections_filter_timeout, hide_already_selected_sections, remove_section_cover, save_sort_order, search_available_sections, section_category_dropdown_changed, update_section_category_dropdown_from_input;

hide_already_selected_sections = function() {
  var selected_section_ids, showing_at_least_one_section;
  selected_section_ids = [];
  showing_at_least_one_section = false;
  $(".sections-for-editing .section").each(function() {
    return selected_section_ids.push($(this).data('section-id'));
  });
  $(".available-sections-table .section").each(function() {
    var el, section_id;
    el = $(this);
    section_id = el.data('section-id');
    if (selected_section_ids.indexOf(section_id) !== -1) {
      return el.hide();
    } else {
      showing_at_least_one_section = true;
      return el.show();
    }
  });
  if (!showing_at_least_one_section) {
    return $(".available-sections-table .no-sections").show();
  } else {
    return $(".available-sections-table .no-sections").hide();
  }
};

apply_section_cover = function() {
  var cover, sections_wrapper;
  cover = $("<div class='sections-for-editing-cover'>Saving order...</div>");
  sections_wrapper = $(".sections-for-editing-wrapper");
  cover.css({
    width: sections_wrapper.width(),
    height: sections_wrapper.height()
  });
  return cover.appendTo(sections_wrapper);
};

remove_section_cover = function() {
  return $(".sections-for-editing-cover").remove();
};

save_sort_order = function() {
  var project_id, sections;
  apply_section_cover();
  project_id = $(".sections-for-editing-wrapper").data('project-id');
  sections = [];
  $(".sections-for-editing-wrapper .section").each(function() {
    return sections.push($(this).data('section-id'));
  });
  return $.ajax({
    url: "/projects/" + project_id + "/sections/reorder",
    type: "POST",
    data: {
      sections: sections
    },
    success: function(data) {
      return remove_section_cover();
    }
  });
};

update_section_category_dropdown_from_input = function() {
  var option, val;
  val = $("#section-category-input").val();
  option = $("#section-category-select option[value='" + val + "']");
  if (option.length > 0) {
    option.attr('selected', true);
    return $("#section-category-input").hide();
  } else {
    $("#section-category-select option[value=Other]").attr('selected', true);
    return $("#section-category-input").show();
  }
};

section_category_dropdown_changed = function() {
  var val;
  val = $("#section-category-select").val();
  if (val !== "Other") {
    $("#section-category-input").hide();
    return $("#section-category-input").val(val);
  } else {
    $("#section-category-input").val('');
    return $("#section-category-input").show();
  }
};

search_available_sections = function() {
  var project_id, query;
  query = $("#available-sections-filter").val();
  project_id = $(".available-sections-table").data('project-id');
  $(".available-sections-table").addClass("loading");
  return $.ajax({
    url: "/projects/" + project_id + "/search-available-sections",
    type: "GET",
    data: {
      query: query
    },
    success: function(data) {
      var new_available_sections;
      new_available_sections = $(data.available_sections_tbody_html);
      $(".available-sections-table tbody.section").remove();
      $(".available-sections-table thead").after(new_available_sections);
      return $(".available-sections-table").removeClass("loading");
    }
  });
};

Rfpez.has_unsaved_changes = false;

$(document).on("ready pjax:success sectionsreloaded", function() {
  hide_already_selected_sections();
  $(".category-sections").sortable({
    forcePlaceholderSize: true
  });
  $(".sections-for-editing").sortable({
    handle: "h5",
    forcePlaceholderSize: true
  });
  $(".sections-for-editing").bind('sortupdate', save_sort_order);
  if ($(".fill-in-blanks")) {
    return add_empty_class_to_inputs();
  }
});

$(document).on("click", ".sow-sidebar a", function(e) {
  if (Rfpez.has_unsaved_changes === true && !confirm('Looks like you have some unsaved changes. Are you sure you want to leave this page?')) {
    return e.preventDefault();
  }
});

$(document).on("click", ".show-more-templates-link", function() {
  var li;
  li = $(this).closest("li");
  li.addClass("loading-more");
  return $.ajax({
    url: $(this).data('href'),
    type: "GET",
    success: function(data) {
      var new_templates;
      new_templates = $(data.html);
      li.before(new_templates);
      li.removeClass('loading-more');
      return li.addClass('all-loaded');
    }
  });
});

$(document).on("click", ".sections-for-editing .remove-button", function(e) {
  var el;
  e.preventDefault();
  el = $(this);
  el.button('loading');
  return $.ajax({
    url: el.data('href'),
    type: "DELETE",
    data: {
      requested_html: "sections_for_editing"
    },
    success: function(data) {
      var new_sections_for_editing;
      new_sections_for_editing = $(data.sections_for_editing_html);
      $(".sections-for-editing-wrapper").replaceWith(new_sections_for_editing);
      $(document).trigger("sectionsreloaded");
      return el.button('reset');
    }
  });
});

$(document).on("click", ".selected-sections .remove-button", function(e) {
  var el;
  e.preventDefault();
  el = $(this);
  el.button('loading');
  return $.ajax({
    url: el.data('href'),
    type: "DELETE",
    data: {
      requested_html: "selected_sections"
    },
    success: function(data) {
      var new_selected_sections;
      new_selected_sections = $(data.selected_sections_html);
      $(".selected-sections").replaceWith(new_selected_sections);
      return hide_already_selected_sections();
    }
  });
});

$(document).on("click", ".section .add-button", function(e) {
  var el;
  e.preventDefault();
  el = $(this);
  el.button('loading');
  return $.ajax({
    url: el.data('href'),
    type: "POST",
    success: function(data) {
      var new_sections_for_editing;
      new_sections_for_editing = $(data.sections_for_editing_html);
      $(".sections-for-editing-wrapper").replaceWith(new_sections_for_editing);
      $(document).trigger("sectionsreloaded");
      $("#add-edit-section-modal").modal('hide');
      hide_already_selected_sections();
      return el.button('reset');
    }
  });
});

$(document).on("click", ".add-section-button", function() {
  $("#edit-section-form").resetForm();
  $("#edit-section-form").find("input[name=section_id]").val('');
  $("#add-edit-section-modal").find(".modal-header h3").text("Add Section");
  $("#add-edit-section-modal").find(".will-fork").hide();
  $("#add-edit-section-modal .section-library-li a").click();
  $("#section-category-select").val("Deliverables");
  section_category_dropdown_changed();
  hide_already_selected_sections();
  return $("#add-edit-section-modal").modal('show');
});

$(document).on("click", ".edit-section-link", function() {
  var body, category, section, section_id, title;
  section = $(this).closest(".section");
  section_id = section.data('section-id');
  title = section.data('section-title');
  body = section.find(".body").html();
  category = section.closest(".category").data('name');
  if (section.data('will-fork') === true) {
    $("#add-edit-section-modal").find(".will-fork").show();
  } else {
    $("#add-edit-section-modal").find(".will-fork").hide();
  }
  $("#add-edit-section-modal").find(".modal-header h3").text("Edit Section '" + title + "'");
  $("#edit-section-form").find("input[name=section_id]").val(section_id);
  $("#edit-section-form").find("input[name=project_section\\[section_category\\]]").val(category);
  $("#edit-section-form").find("input[name=project_section\\[title\\]]").val(title);
  $("#edit-section-form").find("textarea[name=project_section\\[body\\]]").data("wysihtml5").editor.setValue(body);
  update_section_category_dropdown_from_input();
  $("#add-edit-section-modal .section-form-li a").click();
  return $("#add-edit-section-modal").modal('show');
});

$(document).on("submit", "#edit-section-form", function(e) {
  var button, el;
  e.preventDefault();
  el = $(this);
  button = el.find(".save-button");
  button.button('loading');
  return el.ajaxSubmit({
    success: function(data) {
      var new_sections_for_editing;
      new_sections_for_editing = $(data.sections_for_editing_html);
      $(".sections-for-editing-wrapper").replaceWith(new_sections_for_editing);
      $(document).trigger("sectionsreloaded");
      $("#add-edit-section-modal").modal('hide');
      return button.button('reset');
    }
  });
});

$(document).on("click", "li.template .preview-button", function() {
  return $(this).closest('div').find('.modal').modal('show');
});

$(document).on("change", "#section-category-select", section_category_dropdown_changed);

$(document).on("click", "tbody.section", function(e) {
  if (!$(e.target).hasClass('add-button')) {
    return $(this).find(".preview").toggle();
  }
});

available_sections_filter_timeout = false;

$(document).on("input", "#available-sections-filter", function() {
  clearTimeout(available_sections_filter_timeout);
  return available_sections_filter_timeout = setTimeout(function() {
    return search_available_sections();
  }, 200);
});

add_empty_class_to_inputs = function() {
  return $(".fill-in-blanks input[type=text]").each(function() {
    if (!$(this).val()) {
      return $(this).addClass('empty');
    } else {
      return $(this).removeClass('empty');
    }
  });
};

$("input[data-variable]").autoGrow({
  comfortZone: 5
});

$(document).on("focus", "input[data-variable]", function() {
  var el;
  el = $(this);
  el.tooltip({
    title: el.data('helper-text'),
    placement: 'bottom',
    trigger: 'manual'
  });
  return el.tooltip('show');
});

$(document).on("keydown", "input[data-variable]", function(e) {
  var index, input, inputs;
  if (e.keyCode === 13 || e.keyCode === 9) {
    inputs = $("input[data-variable]");
    index = inputs.index(this) + 1;
    while (index < inputs.length) {
      input = $(inputs[index]);
      if (input.val() === "") {
        e.preventDefault();
        return input.select();
      }
      index++;
    }
  }
});

$(document).on("blur", "input[data-variable]", function() {
  return $(this).tooltip('hide');
});

$(document).on("input blur", "input[data-variable]", function(e) {
  var el, variableName, variableValue;
  Rfpez.has_unsaved_changes = true;
  el = $(this);
  variableName = el.data('variable');
  variableValue = el.val();
  $("input[data-variable=" + variableName + "]").each(function() {
    $(this).val(variableValue);
    return $(this).trigger("input.autogrow");
  });
  return add_empty_class_to_inputs();
});

$(document).on("change", "#project-type-select", function() {
  if ($(this).val() === "Other") {
    return $("#new-project-type-input").removeClass('hide');
  } else {
    return $("#new-project-type-input").val('').addClass('hide');
  }
});
