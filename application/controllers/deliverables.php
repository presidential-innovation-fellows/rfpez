<?php

class Deliverables_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'project_exists');

    $this->filter('before', 'i_am_collaborator');

    $this->filter('before', 'deliverable_exists')->only(array('update', 'delete'));
  }

  public function action_save_order() {
    $project = Config::get('project');
    $current_deliverables_list = $project->deliverables()->lists('id');
    $new_deliverables_list = array();
    $input_deliverables = Input::get('deliverable_ids');

    foreach ($input_deliverables as $deliverable_id) {
      if (in_array($deliverable_id, $current_deliverables_list))
        $new_deliverables_list[] = $deliverable_id;
    }

    $i = 0;
    foreach ($new_deliverables_list as $deliverable_id) {
      $deliverable = Deliverable::find($deliverable_id);
      $deliverable->sort_order = $i;
      $deliverable->save();
      $i++;
    }

    return Response::json("success");
  }

  public function action_create() {
    $project = Config::get('project');
    $deliverable = new Deliverable(Input::json(true));
    $deliverable->project_id = $project->id;
    $deliverable->save();
    $deliverable = Deliverable::find($deliverable->id); // hack to refresh from db
    return Response::json($deliverable->to_array());
  }

  public function action_update() {
    $deliverable = Config::get('deliverable');
    $deliverable->fill(Input::json(true));
    $deliverable->save();
    return Response::json($deliverable->to_array());
  }

  public function action_delete() {
    Config::get('deliverable')->delete();
    return Response::json("success");
  }

}

Route::filter('project_exists', function() {
  $id = Request::$route->parameters[0];
  $project = Project::find($id);
  if (!$project) return Redirect::to('/');
  Config::set('project', $project);
});

Route::filter('i_am_collaborator', function() { // also allowed if user is ADMIN
  $project = Config::get('project');
  if (!$project->is_mine() && !Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)) return Redirect::to('/');
});

Route::filter('deliverable_exists', function() {
  $id = Request::$route->parameters[1];
  $project = Config::get('project');
  $deliverable = $project->deliverables()->where_id($id)->first();
  if (!$deliverable) return Redirect::to('/');
  Config::set('deliverable', $deliverable);
});
