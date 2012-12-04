<?php

class Api_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    Config::set('application.profiler', false);

    $this->filter('before', 'auth_with_key_vendor')->only(array('post_project_question', 'get_my_bids', 'get_my_bid',
                                                                'delete_my_bid', 'get_my_notifications',
                                                                'update_notification', 'get_my_account'));

    $this->filter('before', 'project_exists')->only(array('get_project', 'get_project_questions',
                                                          'post_project_question', 'get_my_bid', 'delete_my_bid'));

    $this->filter('before', 'my_bid_exists')->only(array('get_my_bid', 'delete_my_bid'));

    $this->filter('before', 'notification_exists_and_is_mine')->only('update_notification');
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

  public function action_get_my_bids() {
    $user = Config::get('api_user');
    return Response::json(Bid::to_array_for_vendor($user->vendor->bids));
  }

  public function action_get_my_bid($project_id) {
    return Response::json(Bid::to_array_for_vendor(Config::get('bid')));
  }

  public function action_delete_my_bid($project_id) {
    if (Config::get('bid')->delete()) {
      return Response::json(array('status' => 'OK'));
    }
  }

  public function action_get_my_notifications() {
    $user = Config::get('api_user');
    return Response::json(Notification::to_array_for_vendor($user->notifications_received));
  }

  public function action_update_notification() {
    $notification = Config::get('notification');

    // currently, all we're changing is read/unread status
    $notification->read = Input::get('read');
    $notification->save();
    $notification = Notification::find($notification->id); // hack for refresh

    return Response::json();
  }

  public function action_get_my_account() {
    $user = Config::get('api_user');
    return Response::json(User::to_array_for_vendor($user));
  }

}

Route::filter('auth_with_key_vendor', function() {
  $key = Input::get('key');

  if ($key != ""){
    $user = User::where_null('banned_at')
                ->where_not_null('api_key')
                ->where_api_key($key)
                ->first();
  }

  if (!isset($user) || !$user) return Response::json(array("error" => 'Authentication failed. Please provide an API key, e.g. `?key=XXXXXXXXXXXXXX`.'), '401');
  if (!$user->vendor) return Response::json(array("error" => 'This method is for vendors only.'), '401');

  Config::set('api_user', $user);
});

Route::filter('project_exists', function() {
  $id = Request::$route->parameters[0];
  $project = Project::where_not_null('posted_to_fbo_at')
                    ->where_id($id)
                    ->first();

  if (!$project) return Response::json(array("error" => "Couldn't find a project with id $id."), '400');

  Config::set('project', $project);
});

Route::filter('my_bid_exists', function() {
  $project = Config::get('project');
  $bid = $project->current_bid_from(Config::get('api_user')->vendor);

  if (!$bid) return Response::json(array("error" => "Couldn't find your bid on project $project->id."), '400');

  Config::set('bid', $bid);
});

Route::filter('notification_exists_and_is_mine', function(){
  $user = Config::get('api_user');
  $id = Request::$route->parameters[0];
  $notification = Notification::find($id);

  if (!$notification)
    return Response::json(array("error" => "Couldn't find notification id $id."), '400');

  if ($notification->target_id != $user->id)
    return Response::json(array("error" => '404 page not found'), '404');

  Config::set('notification', $notification);
});
