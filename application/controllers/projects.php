<?php

class Projects_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only')->except(array('show', 'index'));

    $this->filter('before', 'project_exists')->except(array('new', 'create', 'mine', 'index'));

    $this->filter('before', 'project_posted')->only(array('show'));

    $this->filter('before', 'i_am_collaborator')->except(array('new', 'create', 'mine', 'index', 'show', 'destroy_collaborator'));

    $this->filter('before', 'i_am_owner')->only(array('destroy_collaborator'));
  }

  public function action_new() {
    $view = View::make('projects.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $project = new Project(Input::get('project'));
    $project->save();

    $project->officers()->attach(Auth::officer()->id, array('owner' => true));

    $sow = Sow::create(array('based_on_sow_template_id' => Input::get('template_id'),
                             'project_id' => $project->id));

    return Redirect::to_route('sow_background', array($project->id));
  }

  public function action_show() {
    $view = View::make('projects.show');
    $view->project = Config::get('project');
    $this->layout->content = $view;
    if (Auth::user()) Auth::user()->view_notification_payload('project', $view->project->id);
  }

  public function action_update() {
    $project = Config::get('project');
    $project->fill(Input::get('project'));
    $project->save();
    return Redirect::to_route('project_admin', array($project->id));
  }

  public function action_mine() {
    $view = View::make('projects.mine');
    $view->projects = Auth::officer()->projects;
    $this->layout->content = $view;
  }

  public function action_admin() {
    $view = View::make('projects.admin');
    $view->project = Config::get('project');
    $this->layout->content = $view;
  }

  public function action_index() {
    $view = View::make('projects.index');
    $view->projects = Project::open_projects()->get();
    $this->layout->content = $view;
  }

  public function action_add_collaborator() {
    $project = Config::get('project');
    $user = User::where_email(Input::get('email'))->first();

    if (!$user) {
      $user = User::new_officer_from_invite(Input::get('email'), Auth::user(), $project);
      if (!$user) return Response::json(array("status" => "dotgovonly"));
      $send_email = false;
    } else {
      $send_email = true;
    }

    if ($user->officer->collaborates_on($project->id)) return Response::json(array("status" => "already exists"));

    $project->officers()->attach($user->officer->id);

    Notification::send("ProjectCollaboratorAdded", array("project" => $project,
                                                         "officer" => $user->officer,
                                                         "actor_id" => Auth::user()->id), $send_email);

    return Response::json(array("status" => "success",
                                "html" => View::make("projects.partials.collaborator_tr")
                                              ->with('officer', $user->officer)
                                              ->with('project', $project)
                                              ->render() ));
  }

  public function action_destroy_collaborator($project_id, $officer_id) {
    $collaborator = ProjectCollaborator::where_project_id($project_id)
                                       ->where_officer_id($officer_id)
                                       ->where_owner(false)
                                       ->first();

    if ($collaborator) $collaborator->delete();

    return Response::json(array("status" => "success"));
  }

  public function action_post_on_fbo() {
    $view = View::make('projects.post_on_fbo');
    $view->project = Config::get('project');
    $this->layout->content = $view;
  }

  public function action_post_on_fbo_post() {
    $solnbr = Input::get('fbo_solnbr');
    $project = Config::get('project');

    if (!preg_match('/^[0-9A-Za-z\-\_\s]+$/', $solnbr)) {
      Helper::flash_errors('Invalid solicitation number format.');
      return Redirect::to_route('project_post_on_fbo', array($project->id))->with_input();
    }

    $context = stream_context_create(array('http'=>array('timeout' => 20)));
    $contents = @file_get_contents('http://rfpez-apis.presidentialinnovationfellows.org/opportunities/fbozombie/'
                                              . $solnbr, false, $context);

    if ($contents === false) {
      Helper::flash_errors("FBO timed out.");
      return $this->try_fbo_api($solnbr);
    }

    $response = json_decode($contents, true);

    if ($this->trySavingContract($response)) {
      return Redirect::to_route('project', array($project->id));
    } else {
      return $this->try_fbo_api($solnbr);
    }
  }

  public function try_fbo_api($solnbr) {
    $project = Config::get('project');
    $contents = @file_get_contents('http://rfpez-apis.presidentialinnovationfellows.org/opportunities?SOLNBR='.$solnbr);
    if ($contents === false) {
      Helper::flash_errors('FBO API timed out.');
      return Redirect::to_route('project_post_on_fbo', array($project->id))->with_input();
    }
    $json = json_decode($contents, true);

    if (count($json["results"]) === 0) {
      Helper::flash_errors("Couldn't find contract on FBO API.");
      return Redirect::to_route('project_post_on_fbo', array($project->id))->with_input();
    }

    $result = $json["results"][0];

    if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i', $result["CONTACT"], $matches)) {
       $email = $matches[0];
    } else {
      $email = "";
    }

    if ($this->trySavingContract(array('solnbr' => $result["SOLNBR"],
                                       'email' => $email,
                                       'agency' => @$result["AGENCY"],
                                       'office' => @$result["OFFICE"],
                                       'title' => $result["SUBJECT"],
                                       'statement_of_work' => $result["DESC"],
                                       'set_aside' => "",
                                       'classification_code' => @$result["CLASSCOD"],
                                       'naics' => @$result["NAICS"],
                                       'response_date' => @$result["RESPDATE"],
                                       'posted_date' => @$result["DATE"]))) {
      return Redirect::to_route('project', array($project->id));
    } else {
      return Redirect::to_route('project_post_on_fbo', array($project->id));
    }
  }

  public function trySavingContract($attributes) {

    $project = Config::get('project');

    preg_match('/([0-9]+) for more info/', implode($attributes), $matches);
    $parsed_solnbr = isset($matches[1]) ? $matches[1] : false;

    if (!isset($attributes["solnbr"])) {
      Helper::flash_errors("Couldn't find that contract on FBO.");
      return false;
    } else if (Project::where_fbo_solnbr($attributes["solnbr"])->first()) {
      Helper::flash_errors("That contract already exists in EasyBid.");
      return false;
    } else if (!preg_match('/'.preg_quote(Auth::user()->email).'/i', implode($attributes))) {
      Helper::flash_errors("Couldn't verify email address.");
      return false;
    } else if ($parsed_solnbr != $project->id) {
      Helper::flash_errors("Couldn't verify notice. Make sure you copy the body text exactly as-is.");
      return false;
    }

    if (!Auth::officer()->is_verified_contracting_officer()) {
      Auth::officer()->verify_with_solnbr($attributes["solnbr"]);
    }

    $project = Config::get('project');
    $project->fbo_solnbr = $attributes["solnbr"];
    $project->body = $project->sow->body;

    if ($due_at = strtotime($attributes["response_date"])) {
      $project->proposals_due_at = date_timestamp_set(new \DateTime(), $due_at);
    }

    $project->save();

    Session::forget('errors');
    return true;
  }

}

Route::filter('project_exists', function() {
  $id = Request::$route->parameters[0];
  $project = Project::find($id);
  if (!$project) return Redirect::to('/');
  Config::set('project', $project);
});

Route::filter('project_posted', function() {
  $project = Config::get('project');

  if ($project->status() != Project::STATUS_WRITING_SOW) return;

  if (!Auth::officer()) return Redirect::to('/');

  if ($project->sow->body) {
    return Redirect::to_route('sow_review', array($project->id));
  } else {
    return Redirect::to_route('sow_background', array($project->id));
  }
});

Route::filter('i_am_collaborator', function() {
  $project = Config::get('project');
  if (!$project->is_mine()) return Redirect::to('/');
});

Route::filter('i_am_owner', function() {
  $project = Config::get('project');
  if (!$project->i_am_owner()) return Redirect::to('/');
});
