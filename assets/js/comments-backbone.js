
(function() {
  var App, AppView, Comment, CommentList, CommentView, Comments, NotificationView;
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
  NotificationView = Backbone.View.extend({
    tagName: "div",
    className: "notification",
    template: _.template("<i class=\"<%= js_parsed.icon %>\"></i>\n<%= js_parsed.text %>"),
    parse: function() {
      var icon, text;
      if (this.model.attributes.notification_type === "Dismissal") {
        text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "'s</a> bid was dismissed. ";
        icon = "icon-thumbs-down";
      } else if (this.model.attributes.notification_type === "Undismissal") {
        text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "'s</a> bid was un-dismissed. ";
        icon = "icon-repeat";
      } else if (this.model.attributes.notification_type === "BidSubmit") {
        text = " <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "</a> submitted a bid. ";
        icon = "icon-list-alt";
      } else if (this.model.attributes.notification_type === "Award") {
        text = " You awarded the contract to <a href=\"" + this.model.attributes.parsed.link + "\">" + this.model.attributes.payload.bid.vendor.company_name + "</a>. ";
        icon = "icon-thumbs-up";
      } else if (this.model.attributes.notification_type === "ProjectCollaboratorAdded") {
        text = " " + this.model.attributes.payload.officer.name + " was added as a collaborator. ";
        icon = "icon-user";
      }
      return {
        text: text != null ? text : this.model.attributes.notification_type,
        icon: icon != null ? icon : "icon-arrow-right"
      };
    },
    initialize: function() {
      this.model.bind("change", this.render, this);
      return this.model.bind("destroy", this.remove, this);
    },
    render: function() {
      this.$el.html(this.template(_.extend(this.model.toJSON(), {
        js_parsed: this.parse()
      })));
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
    addOne: function(model) {
      var html, view;
      if (model.attributes.notification_type) {
        view = new NotificationView({
          model: model
        });
      } else {
        view = new CommentView({
          model: model
        });
      }
      html = view.render().el;
      return $(".comments-list").append(html);
    },
    addAll: function() {
      return Comments.each(this.addOne);
    }
  });
  App = false;
  Comments = false;
  return Rfpez.Backbone.Comments = function(project_id, initialModels) {
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
})();
