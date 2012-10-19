<?php

class Comments_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'project_exists');

    $this->filter('before', 'i_am_collaborator');

    $this->filter('before', 'comment_exists')->only(array('destroy'));

    $this->filter('before', 'comment_is_mine')->only(array('destroy'));
  }

  public function action_index() {
    $view = View::make('comments.index');
    $view->project = Config::get('project');
    $view->comments = $view->project->comments;
    $this->layout->content = $view;

    $comment_ids = array();
    foreach($view->comments as $comment) $comment_ids[] = $comment->id;
    Auth::user()->view_notification_payload("comment", $comment_ids, "read");
  }

  public function action_create() {
    $comment = new Comment(array('project_id' => Config::get('project')->id,
                                 'officer_id' => Auth::officer()->id));
    $comment->body = Input::get('body');
    $comment->save();

    $c = Comment::find($comment->id);

    foreach($c->project->officers as $officer) {
      if (Auth::officer()->id != $officer->id)
        Notification::send("Comment", array('comment' => $c,
                                            'target_id' => $officer->user->id));
    }

    return Response::json(array('status' => 'success',
                                'html' => View::make('comments.partials.comment')->with('comment', $c)->render() ));
  }

  public function action_destroy() {
    $comment = Config::get('comment');
    $comment->delete();
    return Response::json(array('status' => 'success'));
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

Route::filter('comment_exists', function() {
  $id = Request::$route->parameters[1];
  $comment = Comment::where_project_id(Config::get('project')->id)
                    ->where_id($id)
                    ->first();
  if (!$comment) return Redirect::to('/');
  Config::set('comment', $comment);
});

Route::filter('comment_is_mine', function() {
  $comment = Config::get('comment');
  if (!$comment->is_mine()) return Redirect::to('/');
});
