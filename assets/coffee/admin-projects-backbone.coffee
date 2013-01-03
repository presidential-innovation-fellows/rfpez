( ->
  Project = Backbone.Model.extend
    validate: (attrs) ->

    defaults: ->

    clear: ->
      @destroy()

  ProjectList = Backbone.Collection.extend
    model: Project

    url: "/projects"

  ProjectView = Backbone.View.extend
    tagName: "tr"

    template: _.template """
      <td><%- id %></td>
      <td><%- title %></td>
      <td><%- fork_count %></td>
      <td>
        <select class="recommended-select">
          <option value="1" <% if (recommended == 1){ %>selected<% } %>>Yes</option>
          <option value="0" <% if (recommended == 0){ %>selected<% } %>>No</option>
        </select>
      </td>
      <td>
        <select class="public-select">
          <option value="1" <% if (public == 1){ %>selected<% } %>>Yes</option>
          <option value="0" <% if (public == 0){ %>selected<% } %>>No</option>
        </select>
      </td>
      <td><%- project_type.name %></td>
    """

    events:
      "change .recommended-select": "update"
      "change .public-select": "update"

    update: ->
      @model.save
        recommended: @$el.find(".recommended-select").val()
        public: @$el.find(".public-select").val()

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
      Projects.bind 'reset', @reset, @
      Projects.bind 'all', @render, @

    reset: ->
      $("#projects-tbody").html('')
      @addAll()

    render: ->
      #

    addOne: (project) ->
      view = new ProjectView({model: project})
      html = view.render().el
      $("#projects-tbody").append(html);

    addAll: ->
      Projects.each @addOne

  App = {}
  Projects = {}

  Rfpez.Backbone.AdminProjects = (initialModels) ->
    Projects = new ProjectList
    App = new AppView({collection: Projects})
    Projects.reset(initialModels)
    return App
)()