var App, AppView, Project, ProjectList, ProjectView, Projects;

Project = Backbone.Model.extend({
  validate: function(attrs) {},
  defaults: function() {},
  clear: function() {
    return this.destroy();
  }
});

ProjectList = Backbone.Collection.extend({
  model: Project,
  url: "/projects"
});

ProjectView = Backbone.View.extend({
  tagName: "tr",
  template: _.template("<td><%= id %></td>\n<td><%= title %></td>\n<td><%= fork_count %></td>\n<td>\n  <select class=\"recommended-select\">\n    <option value=\"1\" <% if (recommended == 1){ %>selected<% } %>>Yes</option>\n    <option value=\"0\" <% if (recommended == 0){ %>selected<% } %>>No</option>\n  </select>\n</td>\n<td>\n  <select class=\"public-select\">\n    <option value=\"1\" <% if (public == 1){ %>selected<% } %>>Yes</option>\n    <option value=\"0\" <% if (public == 0){ %>selected<% } %>>No</option>\n  </select>\n</td>\n<td><%= project_type.name %></td>"),
  events: {
    "change .recommended-select": "update",
    "change .public-select": "update"
  },
  update: function() {
    return this.model.save({
      recommended: this.$el.find(".recommended-select").val(),
      "public": this.$el.find(".public-select").val()
    });
  },
  initialize: function() {
    this.model.bind("change", this.render, this);
    return this.model.bind("destroy", this.remove, this);
  },
  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
    return this;
  },
  clear: function() {
    return this.model.clear();
  }
});

AppView = Backbone.View.extend({
  initialize: function() {
    Projects.bind('reset', this.reset, this);
    return Projects.bind('all', this.render, this);
  },
  reset: function() {
    $("#projects-tbody").html('');
    return this.addAll();
  },
  render: function() {},
  addOne: function(project) {
    var html, view;
    view = new ProjectView({
      model: project
    });
    html = view.render().el;
    return $("#projects-tbody").append(html);
  },
  addAll: function() {
    return Projects.each(this.addOne);
  }
});

App = {};

Projects = {};

Rfpez.Backbone.AdminProjects = function(initialModels) {
  Projects = new ProjectList;
  App = new AppView({
    collection: Projects
  });
  Projects.reset(initialModels);
  return App;
};
