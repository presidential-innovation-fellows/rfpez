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

  addOne: (comment) ->
    view = new CommentView({model: comment})
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