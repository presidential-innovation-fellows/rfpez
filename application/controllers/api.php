<?php

class Api_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    Config::set('application.profiler', false);

    $this->filter('before', 'auth_with_key')->only('post_project_question');

    $this->filter('before', 'vendor_only')->only('post_project_question');

    $this->filter('before', 'project_exists')->only(array('get_project', 'get_project_questions',
                                                          'get_project_question', 'post_project_question'));
  }

  public function action_get_projects() {
    $projects = Project::where_not_null('posted_to_fbo_at')->get();
    return Response::json(Project::to_array_for_vendor($projects));
  }

  public function action_get_project($id) {
    $project = Config::get('project');
    return Response::json(Project::to_array_for_vendor($project));
  }

  public function action_get_project_questions() {
    $project = Config::get('project');
    return Response::json(Question::to_array_for_vendor($project->questions));
  }

  public function action_get_project_question($project_id, $question_id) {
    $question = Question::where_project_id($project_id)->where_id($question_id)->first();
    return Response::json(Question::to_array_for_vendor($question));
  }

  public function action_post_project_question($project_id) {
    $vendor = Config::get('api_user')->vendor;
    $question = new Question(array('project_id' => $project_id,
                                   'question' => Input::get('question')));

    $question->vendor_id = $vendor->id;

    if ($question->validator()->passes()) {
      $question->save();
      $question = Question::find($question->id); // reload hack to fix datetime fields
      return Response::json(Question::to_array_for_vendor($question));
    } else {
      $response = array('errors' => $question->validator()->errors->all());
      return Response::json($response, '400');
    }
  }

}

Route::filter('auth_with_key', function() {
  $key = Input::get('key');
  $user = User::where_null('banned_at')
              ->where_not_null('api_key')
              ->where_api_key($key)
              ->first();

  if (!$user) return Response::json('Authentication failed. Please provide an API key, e.g. `?key=XXXXXXXXXXXXXX`.', '401');

  Config::set('api_user', $user);
});

Route::filter('vendor_only', function() {
  $user = Config::get('api_user');

  if (!$user->vendor) return Response::json('This method is for vendors only.', '401');
});

Route::filter('project_exists', function() {
  $id = Request::$route->parameters[0];
  $project = Project::where_not_null('posted_to_fbo_at')
                    ->where_id($id)
                    ->first();

  if (!$project) return Response::json("Couldn't find a project with id $id.", '400');

  Config::set('project', $project);
});
