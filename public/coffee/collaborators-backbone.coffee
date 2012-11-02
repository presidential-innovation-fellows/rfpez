Collaborator = Backbone.Model.extend
  validate: (attrs) ->
    errors = []

    if (!attrs.User.email)
      return true # Throws an error but does not trigger the errorAdding events
    else if (!attrs.User.email.match(/.gov$/i))
      errors.push "Sorry, .gov addresses only"
    else if (!attrs.id && Collaborators.existing_emails().indexOf(attrs.User.email.toLowerCase()) != -1)
      errors.push "That collaborator already exists."

    if errors.length > 0
      App.trigger 'errorAdding', errors
      return errors

  defaults: ->
    owner: false

  clear: ->
    @destroy()

CollaboratorList = Backbone.Collection.extend
  existing_emails: ->
    @.map (c) ->
      return c.attributes.User.email.toLowerCase()

  model: Collaborator

CollaboratorView = Backbone.View.extend
  tagName: "tr"

  template: _.template """
    <td class="email"><%= User.email %></td>
    <td>
      <% if (pivot.owner === "1") { %>
        <i class="icon-star"></i>
      <% } %>
    </td>
    <td>
      <% if (pivot.owner !== "1") { %>
        <button class="btn btn-danger">Remove</button>
      <% } else { %>
        Can't remove the owner.
      <% } %>
    </td>
  """

  events:
    "click .btn.btn-danger": "clear"

  initialize: ->
    @model.bind "change", @render, @
    @model.bind "destroy", @remove, @

  render: ->
    @$el.html @template(@model.toJSON())
    return @

  clear: ->
    @model.clear()

AppView = Backbone.View.extend

  initialize: ->
    Collaborators.bind 'add', @addOne, @
    Collaborators.bind 'reset', @reset, @
    Collaborators.bind 'all', @render, @
    @bind 'errorAdding', @showError

    $("#add-collaborator-form").submit @addNew

    # Collaborators.fetch()

  addNew: (e) ->
    e.preventDefault()
    email = $("#add-collaborator-form input[name=email]").val()
    $("#add-collaborator-form input[name=email]").val('')

    Collaborators.create
      User:
        email: email
      pivot:
        owner: 0
    ,
      error: (obj, err) ->
        obj.clear()

  showError: (errors) ->
    $("#add-collaborator-form button").flash_button_message("warning", errors[0])

  reset: ->
    @addAll()

  render: ->
    #

  addOne: (collaborator) ->
    view = new CollaboratorView({model: collaborator})
    html = view.render().el
    $("#collaborators-tbody").append(html);

  addAll: ->
    Collaborators.each @addOne

App = false
Collaborators = false

Rfpez.Backbone.Collaborators = (project_id, initialModels) ->
  Collaborators = new CollaboratorList
  initialCollection = Collaborators;
  App = new AppView({collection: initialCollection})
  initialCollection.reset(initialModels)
  initialCollection.url = "/projects/#{project_id}/collaborators"
  return App