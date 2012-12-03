<?php

class Api_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    Config::set('application.profiler', false);

    // $this->filter('before', 'project_exists');
  }

  public function action_get_projects() {
    $projects = Project::where_not_null('posted_to_fbo_at')->get();
    return Response::json(Project::to_array_for_vendor($projects));
  }

  public function action_get_project($id) {
    $project = Project::find($id);
    return Response::json(Project::to_array_for_vendor($project));
  }

}


Route::filter('comment_is_mine', function() {
});
