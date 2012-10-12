<?php

class Comments_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'project_exists');

    $this->filter('before', 'i_am_collaborator');
  }

  public function action_index() {
    $view = View::make('comments.index');
    $view->project = Config::get('project');
    $this->layout->content = $view;
  }

  public function action_create() {
    $comment = new Comment(array('project_id' => Config::get('project')->id,
                                 'officer_id' => Auth::officer()->id));
    $comment->body = Input::get('body');
    $comment->save();

    $c = Comment::find($comment->id);

    return Response::json(array('status' => 'success',
                                'html' => View::make('comments.partials.comment')->with('comment', $c)->render() ));
  }
}

Route::filter('project_exists', function() {
  $id = Request::$route->parameters[0];
  $project = Project::find($id);
  if (!$project) return Redirect::to('/');
  Config::set('project', $project);
});

Route::filter('i_am_collaborator', function() {
  $project = Config::get('project');
  if (!$project->is_mine()) return Redirect::to('/');
});
