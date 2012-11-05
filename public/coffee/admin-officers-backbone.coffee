Officer = Backbone.Model.extend
  validate: (attrs) ->

  defaults: ->

  clear: ->
    @destroy()

OfficerList = Backbone.Collection.extend
  model: Officer

  url: "/officers"

OfficerView = Backbone.View.extend
  tagName: "tr"

  template: _.template """
    <td><%= id %></td>
    <td><%= name %></td>
    <td><%= title %></td>
    <td><%= User.email %></td>
    <td>
      <div class="not-user-<%= User.id %>">
        <% if (role == 3 && !isSuperAdmin) { %>
          This officer is a super-admin.
        <% } else { %>
          <select class="user_role_select">
            <option value="0" <% if(role == 0){ %>selected <% } %>>Program Officer</option>
            <option value="1" <% if(role == 1){ %>selected <% } %>>Contracting Officer</option>
            <option value="2" <% if(role == 2){ %>selected <% } %>>Admin</option>
            <% if (isSuperAdmin) { %>
              <option value="3" <% if(role == 3){ %>selected <% } %>>Super Admin</option>
            <% } %>
          </select>
        <% } %>
      </div>
      <div class="only-user only-user-<%= User.id %>">
        You're a <%= role_text %>.
      </div>
    </td>
  """

  events:
    "change .user_role_select": "update"

  initialize: ->
    @model.bind "change", @render, @
    @model.bind "destroy", @remove, @

  render: ->
    @$el.html @template(_.extend(@model.toJSON(), {isSuperAdmin: App.options.isSuperAdmin}))
    return @

  update: ->
    @model.save
      role: @$el.find(".user_role_select").val()

  clear: ->
    @model.clear()

AppView = Backbone.View.extend

  initialize: ->
    Officers.bind 'reset', @reset, @
    Officers.bind 'all', @render, @

  reset: ->
    $("#officers-tbody").html('')
    @addAll()

  render: ->
    #

  addOne: (officer) ->
    view = new OfficerView({model: officer})
    html = view.render().el
    $("#officers-tbody").append(html);

  addAll: ->
    Officers.each @addOne

App = {}
Officers = {}

Rfpez.Backbone.AdminOfficers = (initialModels) ->
  isSuperAdmin = $("body").hasClass('super-admin')
  Officers = new OfficerList
  App = new AppView({collection: Officers, isSuperAdmin: isSuperAdmin})
  Officers.reset(initialModels)
  return App