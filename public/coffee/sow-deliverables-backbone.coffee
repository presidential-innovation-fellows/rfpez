Deliverable = Backbone.Model.extend
  validate: (attrs) ->

  defaults: ->
    date: ""
    name: ""
    sort_order: $("#deliverables-tbody tr").length

  clear: ->
    @destroy()

DeliverableList = Backbone.Collection.extend
  model: Deliverable

DeliverableView = Backbone.View.extend
  tagName: "tr"

  template: _.template """
    <td>
      <input type="text" placeholder="Deliverable Name" class="name-input" value="<%= name %>">
    </td>
    <td>
      <span class="input-append datepicker date">
        <input type="text" placeholder="Due Date" class="date-input" value="<%= date %>">
        <span class="add-on">
          <i class="icon-calendar"></i>
        </span>
      </span>
    </td>
    <td>
      <a class="btn remove-deliverable-button"><i class="icon-trash"></i></a>
    </td>
  """

  events:
    "click .remove-deliverable-button": "clear"
    "input .name-input": "updateWithDelay"
    "input .date-input": "updateWithDelay"
    "change .date-input": "updateWithDelay"

  initialize: ->
    @model.bind "create", @render, @
    @model.bind "destroy", @remove, @

  render: ->
    @$el.html @template(@model.toJSON())
    @$el.find('.datepicker').datepicker()
    return @

  updateWithDelay: ->
    Rfpez.has_unsaved_changes = true
    if @updateTimeout then clearTimeout(@updateTimeout)
    @updateTimeout = setTimeout =>
      @update()
    , 200

  update: ->
    Rfpez.has_unsaved_changes = false
    @model.save
      name: @$el.find(".name-input").val()
      date: @$el.find(".date-input").val()
      sort_order: $("#deliverables-tbody tr").index(@$el)

  clear: ->
    @model.clear()

AppView = Backbone.View.extend

  initialize: ->
    Deliverables.bind 'add', @addOne, @
    Deliverables.bind 'reset', @reset, @
    Deliverables.bind 'all', @render, @

    $("#deliverables-tbody").bind 'sortupdate', =>
      $.ajax
        url: "/projects/#{@options.project_id}/deliverables/order"
        method: "PUT"
        data:
          deliverable_ids: "bar"
        success: ->
          console.log 'success'

    $(document).on "click", ".add-deliverable-timeline-button", =>
      @addNew()


  reset: ->
    $("#deliverables-tbody").html('')
    @addAll()

  render: ->
    $("#deliverables-tbody").sortable
      forcePlaceholderSize: true

  addNew: ->
    Deliverables.create()

  addOne: (deliverable) ->
    view = new DeliverableView({model: deliverable})
    html = view.render().el
    $("#deliverables-tbody").append(html);

  addAll: ->
    Deliverables.each @addOne

App = {}
Deliverables = {}

Rfpez.Backbone.SowDeliverables = (project_id, initialModels) ->
  Deliverables = new DeliverableList
  App = new AppView({collection: Deliverables, project_id: project_id})
  Deliverables.reset(initialModels)
  Deliverables.url = "/projects/#{project_id}/deliverables"
  return App