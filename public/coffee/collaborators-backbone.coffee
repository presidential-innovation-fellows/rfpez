$ ->
  Collaborator = Backbone.Model.extend
    validate: (attrs) ->
      if (!attrs.User.email)
        "please enter email"
      else if (!attrs.User.email.match(/.gov$/i))
        ".gov only"
      else if (!attrs.id && Collaborators.existing_emails().indexOf(attrs.User.email) != -1)
        "already exists"

    defaults: ->
      owner: false

    clear: ->
      @destroy()

  CollaboratorList = Backbone.Collection.extend
    initialize: (models, options) ->
      @url = "/projects/#{options.project_id}/collaborators"

    existing_emails: ->
      @.map (c) ->
        return c.attributes.User.email

    model: Collaborator

  Collaborators = new CollaboratorList([], {project_id: $(".collaborators-table").data('project-id')})

  CollaboratorView = Backbone.View.extend
    tagName: "tr"

    template: _.template """
      <td><%= User.email %></td>
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

      $("#add-collaborator-form").submit @addNew

      Collaborators.fetch()

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

  App = new AppView