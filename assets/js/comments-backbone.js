var App, AppView, Comment, CommentList, CommentView, Comments;

Comment = Backbone.Model.extend({
  validate: function(attrs) {
    if (!attrs.body) {
      return true;
    }
  },
  defaults: function() {
    return {
      owner: false
    };
  },
  clear: function() {
    return this.destroy();
  }
});

CommentList = Backbone.Collection.extend({
  model: Comment
});

CommentView = Backbone.View.extend({
  tagName: "div",
  className: "well comment",
  template: _.template("<div class=\"body\">\n  <span class=\"author\">\n    <%= officer.name %>\n  </span>\n  <%= body %>\n</div>\n<span class=\"timestamp\">\n  <span class=\"posted-at\">Posted <span class=\"timeago\" title=\"<%= formatted_created_at %>\"></span></span>\n</span>\n<a class=\"delete-comment only-user only-user-<%= officer.user_id %>\">Delete</a>"),
  events: {
    "click a.delete-comment": "clear"
  },
  initialize: function() {
    this.model.bind("change", this.render, this);
    return this.model.bind("destroy", this.remove, this);
  },
  render: function() {
    this.$el.html(this.template(this.model.toJSON()));
    this.$el.find(".timeago").timeago();
    return this;
  },
  clear: function() {
    return this.model.clear();
  }
});

AppView = Backbone.View.extend({
  initialize: function() {
    Comments.bind('add', this.addOne, this);
    Comments.bind('reset', this.reset, this);
    Comments.bind('all', this.render, this);
    this.bind('errorAdding', this.showError);
    return $("#add-comment-form").submit(this.addNew);
  },
  addNew: function(e) {
    var dateString;
    e.preventDefault();
    dateString = new Date().toISOString();
    Comments.create({
      officer: {
        name: $("#add-comment-form").data('officer-name'),
        user_id: $("#add-comment-form").data('officer-user-id')
      },
      body: $("#add-comment-form textarea").val(),
      formatted_created_at: dateString
    }, {
      error: function(obj, err) {
        return obj.clear();
      }
    });
    return $("#add-comment-form").resetForm();
  },
  showError: function(errors) {
    return alert(errors[0]);
  },
  reset: function() {
    $(".comments-list").html('');
    return this.addAll();
  },
  render: function() {},
  addOne: function(comment) {
    var html, view;
    view = new CommentView({
      model: comment
    });
    html = view.render().el;
    return $(".comments-list").append(html);
  },
  addAll: function() {
    return Comments.each(this.addOne);
  }
});

App = false;

Comments = false;

Rfpez.Backbone.Comments = function(project_id, initialModels) {
  var initialCollection;
  Comments = new CommentList;
  initialCollection = Comments;
  App = new AppView({
    collection: initialCollection
  });
  initialCollection.reset(initialModels);
  initialCollection.url = "/projects/" + project_id + "/comments";
  return App;
};
