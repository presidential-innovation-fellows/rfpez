var App, AppView, Officer, OfficerList, OfficerView, Officers;

Officer = Backbone.Model.extend({
  validate: function(attrs) {},
  defaults: function() {},
  clear: function() {
    return this.destroy();
  }
});

OfficerList = Backbone.Collection.extend({
  model: Officer,
  url: "/officers"
});

OfficerView = Backbone.View.extend({
  tagName: "tr",
  template: _.template("<td><%= id %></td>\n<td><%= name %></td>\n<td><%= title %></td>\n<td><%= User.email %></td>\n<td>\n  <div class=\"not-user-<%= User.id %>\">\n    <% if (role == 3 && !isSuperAdmin) { %>\n      This officer is a super-admin.\n    <% } else { %>\n      <select class=\"user_role_select\">\n        <option value=\"0\" <% if(role == 0){ %>selected <% } %>>Program Officer</option>\n        <option value=\"1\" <% if(role == 1){ %>selected <% } %>>Contracting Officer</option>\n        <option value=\"2\" <% if(role == 2){ %>selected <% } %>>Admin</option>\n        <% if (isSuperAdmin) { %>\n          <option value=\"3\" <% if(role == 3){ %>selected <% } %>>Super Admin</option>\n        <% } %>\n      </select>\n    <% } %>\n  </div>\n  <div class=\"only-user only-user-<%= User.id %>\">\n    You're a <%= role_text %>.\n  </div>\n</td>\n<td>\n  <% if (role != 3){ %>\n    <div class=\"super-admin-only\">\n      <div class=\"not-user-<%= User.id %>\">\n        <% if (!User.banned_at){ %>\n          <a class=\"btn btn-danger ban-button btn-mini\">Ban Officer</a>\n        <% } else { %>\n          <a class=\"btn unban-button btn-mini\">Un-Ban Officer</a>\n        <% } %>\n      </div>\n    </div>\n  <% } %>\n</td>"),
  events: {
    "change .user_role_select": "update",
    "click .ban-button": "ban",
    "click .unban-button": "unban"
  },
  initialize: function() {
    this.model.bind("change", this.render, this);
    return this.model.bind("destroy", this.remove, this);
  },
  render: function() {
    this.$el.html(this.template(_.extend(this.model.toJSON(), {
      isSuperAdmin: App.options.isSuperAdmin
    })));
    return this;
  },
  ban: function() {
    if (confirm('Are you sure you want to ban this officer? This could have unintended consequences if they were the only officer on a project.')) {
      return this.model.save({
        command: "ban"
      });
    }
  },
  unban: function() {
    return this.model.save({
      command: "unban"
    });
  },
  update: function() {
    return this.model.save({
      role: this.$el.find(".user_role_select").val()
    });
  },
  clear: function() {
    return this.model.clear();
  }
});

AppView = Backbone.View.extend({
  initialize: function() {
    Officers.bind('reset', this.reset, this);
    return Officers.bind('all', this.render, this);
  },
  reset: function() {
    $("#officers-tbody").html('');
    return this.addAll();
  },
  render: function() {},
  addOne: function(officer) {
    var html, view;
    view = new OfficerView({
      model: officer
    });
    html = view.render().el;
    return $("#officers-tbody").append(html);
  },
  addAll: function() {
    return Officers.each(this.addOne);
  }
});

App = {};

Officers = {};

Rfpez.Backbone.AdminOfficers = function(initialModels) {
  var isSuperAdmin;
  isSuperAdmin = $("body").hasClass('super-admin');
  Officers = new OfficerList;
  App = new AppView({
    collection: Officers,
    isSuperAdmin: isSuperAdmin
  });
  Officers.reset(initialModels);
  return App;
};
