( ->
  Comment = Backbone.Model.extend
    validate: (attrs) ->
      if !attrs.body
        return true

    defaults: ->
      owner: false

    clear: ->
      @destroy()

  CommentList = Backbone.Collection.extend
    model: Comment

  CommentView = Backbone.View.extend
    tagName: "div"
    className: "well comment"

    template: _.template """
      <div class="body">
        <span class="author">
          <%= officer.name %>
        </span>
        <%= body %>
      </div>
      <span class="timestamp">
        <span class="posted-at">Posted <span class="timeago" title="<%= formatted_created_at %>"></span></span>
      </span>
      <a class="delete-comment only-user only-user-<%= officer.user_id %>">Delete</a>
    """

    events:
      "click a.delete-comment": "clear"

    initialize: ->
      @model.bind "change", @render, @
      @model.bind "destroy", @remove, @

    render: ->
      @$el.html @template(@model.toJSON())
      @$el.find(".timeago").timeago()
      return @

    clear: ->
      @model.clear()

  NotificationView = Backbone.View.extend
    tagName: "div"
    className: "notification"

    template: _.template """
      <i class="icon-arrow-right"></i>
      <%= js_parsed %>
    """
    parse: ->
      if @model.attributes.notification_type is "Dismissal"
        """
          <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.company_name}'s</a> bid was dismissed.
        """
      else if @model.attributes.notification_type is "Undismissal"
        """
          <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.company_name}'s</a> bid was un-dismissed.
        """
      else if @model.attributes.notification_type is "BidSubmit"
        """
          <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.company_name}</a> submitted a bid.
        """
      else if @model.attributes.notification_type is "Award"
        """
          You awarded the contract to <a href="#{@model.attributes.parsed.link}">#{@model.attributes.payload.bid.vendor.company_name}</a>.
        """
      else if @model.attributes.notification_type is "ProjectCollaboratorAdded"
        """
          #{@model.attributes.payload.officer.name} was added as a collaborator.
        """


      else
        @model.attributes.notification_type

    # events:

    initialize: ->
      @model.bind "change", @render, @
      @model.bind "destroy", @remove, @

    render: ->
      @$el.html @template(_.extend(@model.toJSON(), js_parsed: @parse()))
      return @

    clear: ->
      @model.clear()

  AppView = Backbone.View.extend

    initialize: ->
      Comments.bind 'add', @addOne, @
      Comments.bind 'reset', @reset, @
      Comments.bind 'all', @render, @
      @bind 'errorAdding', @showError

      $("#add-comment-form").submit @addNew

    addNew: (e) ->
      e.preventDefault()
      dateString = new Date().toISOString()

      Comments.create
        officer:
          name: $("#add-comment-form").data('officer-name')
          user_id: $("#add-comment-form").data('officer-user-id')
        body: $("#add-comment-form textarea").val()
        formatted_created_at: dateString
      ,
        error: (obj, err) ->
          obj.clear()

      $("#add-comment-form").resetForm()

    showError: (errors) ->
      alert errors[0]

    reset: ->
      $(".comments-list").html('')
      @addAll()

    render: ->
      #

    addOne: (model) ->
      if model.attributes.notification_type
        view = new NotificationView({model: model})
      else
        view = new CommentView({model: model})
      html = view.render().el
      $(".comments-list").append(html);

    addAll: ->
      Comments.each @addOne

  App = false
  Comments = false

  Rfpez.Backbone.Comments = (project_id, initialModels) ->
    Comments = new CommentList
    initialCollection = Comments
    App = new AppView({collection: initialCollection})
    initialCollection.reset(initialModels)
    initialCollection.url = "/projects/#{project_id}/comments"
    return App
)()