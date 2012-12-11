<?php

class Questions_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'vendor_only')->only(array('create'));

    $this->filter('before', 'officer_only')->only(array('update'));

    $this->filter('before', 'question_exists')->only(array('update'));

    $this->filter('before', 'i_am_collaborator')->only(array('update'));
  }

  public function action_create() {
    $project = Project::find(Input::get('project_id'));
    if (!$project || !$project->question_period_is_open()) {
      return Redirect::to_route('project', $project->id);
    }

    $question = new Question(array('project_id' => Input::get('project_id'),
                                   'question' => Input::get('question')));
    $question->vendor_id = Auth::user()->vendor->id;

    if ($question->validator()->passes()) {
      $question->save();
      return Response::json(array("status" => "success",
                                  "question" => $question->to_array(),
                                  "html" => View::make('projects.partials.question')->with('question', $question)->render()));
    } else {
      return Response::json(array("status" => "error", "errors" => $question->validator()->errors->all()));
    }
  }

  public function action_update() {
    $question = Config::get('question');
    $answer = trim(Input::get('answer'));

    if ($answer && $answer != "") {
      $question->answer = $answer;
      $question->answered_by = Auth::officer()->id;
      $question->save();
      return Response::json(array("status" => "success",
                                  "question" => $question->to_array(),
                                  "html" => View::make('projects.partials.question')->with('question', $question)->render()));
    } else {
      return Response::json(array("status" => "error", "errors" => array('No answer provided.')));
    }

  }

}

Route::filter('question_exists', function() {
  $id = Request::$route->parameters[0];
  $question = Question::find($id);
  if (!$question) return Redirect::to('/');
  Config::set('question', $question);
});

Route::filter('i_am_collaborator', function() {
  $question = Config::get('question');
  if (!$question->project->is_mine()) return Redirect::to('/');
});
