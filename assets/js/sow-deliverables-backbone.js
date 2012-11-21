var App, AppView, Deliverable, DeliverableList, DeliverableView, Deliverables;

Deliverable = Backbone.Model.extend({
  validate: function(attrs) {},
  defaults: function() {
    return {
      date: "",
      name: "",
      sort_order: $("#deliverables-tbody tr").length
    };
  },
  clear: function() {
    return this.destroy();
  }
});

DeliverableList = Backbone.Collection.extend({
  model: Deliverable
});

DeliverableView = Backbone.View.extend({
  tagName: "tr",
  template: _.template("<td>\n  <input type=\"text\" placeholder=\"Deliverable Name\" class=\"name-input\" value=\"<%= name %>\">\n</td>\n<td>\n  <div class=\"input-append date datepicker-wrapper\">\n    <input type=\"text\" placeholder=\"Due Date\" class=\"date-input\" value=\"<%= date %>\" />\n    <span class=\"add-on\">\n      <i class=\"icon-calendar\"></i>\n    </span>\n  </div>\n</td>\n<td>\n  <a class=\"btn remove-deliverable-button\"><i class=\"icon-trash\"></i></a>\n</td>"),
  events: {
    "click .remove-deliverable-button": "clear",
    "input .name-input": "updateWithDelay",
    "input .date-input": "updateWithDelay",
    "change .date-input": "updateWithDelay"
  },
  initialize: function() {
    this.model.bind("change", this.updateId, this);
    this.model.bind("create", this.render, this);
    return this.model.bind("destroy", this.remove, this);
  },
  render: function() {
    var _ref, _ref1;
    this.$el.html(this.template(this.model.toJSON()));
    this.$el.find('.datepicker-wrapper').datepicker();
    this.$el.data('id', (_ref = this.model) != null ? (_ref1 = _ref.attributes) != null ? _ref1.id : void 0 : void 0);
    return this;
  },
  updateWithDelay: function() {
    var _this = this;
    Rfpez.has_unsaved_changes = true;
    if (this.updateTimeout) {
      clearTimeout(this.updateTimeout);
    }
    return this.updateTimeout = setTimeout(function() {
      return _this.update();
    }, 200);
  },
  update: function() {
    Rfpez.has_unsaved_changes = false;
    return this.model.save({
      name: this.$el.find(".name-input").val(),
      date: this.$el.find(".date-input").val(),
      sort_order: $("#deliverables-tbody tr").index(this.$el)
    });
  },
  updateId: function() {
    var _ref, _ref1;
    return this.$el.data('id', (_ref = this.model) != null ? (_ref1 = _ref.attributes) != null ? _ref1.id : void 0 : void 0);
  },
  clear: function() {
    return this.model.clear();
  }
});

AppView = Backbone.View.extend({
  initialize: function() {
    var _this = this;
    Deliverables.bind('add', this.addOne, this);
    Deliverables.bind('reset', this.reset, this);
    Deliverables.bind('all', this.render, this);
    $("#deliverables-tbody").bind('sortupdate', function() {
      var ordered_ids;
      ordered_ids = [];
      $("#deliverables-tbody tr").each(function() {
        return ordered_ids.push($(this).data('id'));
      });
      return $.ajax({
        url: "/projects/" + _this.options.project_id + "/deliverables/order",
        type: "PUT",
        data: {
          deliverable_ids: ordered_ids
        }
      });
    });
    return $(document).on("click", ".add-deliverable-timeline-button", function() {
      return _this.addNew();
    });
  },
  reset: function() {
    $("#deliverables-tbody").html('');
    return this.addAll();
  },
  addNew: function() {
    return Deliverables.create();
  },
  addOne: function(deliverable) {
    var html, view;
    view = new DeliverableView({
      model: deliverable
    });
    html = view.render().el;
    return $("#deliverables-tbody").append(html);
  },
  render: function() {
    $('#deliverables-tbody').sortable('destroy');
    return $("#deliverables-tbody").sortable({
      forcePlaceholderSize: true
    });
  },
  addAll: function() {
    return Deliverables.each(this.addOne);
  }
});

App = {};

Deliverables = {};

Rfpez.Backbone.SowDeliverables = function(project_id, initialModels) {
  Deliverables = new DeliverableList;
  App = new AppView({
    collection: Deliverables,
    project_id: project_id
  });
  Deliverables.reset(initialModels);
  Deliverables.url = "/projects/" + project_id + "/deliverables";
  return App;
};
