<?php

class Projects_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only')->except(array('show', 'index'));

    $this->filter('before', 'project_exists')->except(array('new', 'create', 'mine', 'index'));

    $this->filter('before', 'project_posted')->only(array('show'));

    $this->filter('before', 'template_exists_and_is_forkable')->only('template_post');

    $this->filter('before', 'i_am_collaborator')->except(array('new', 'create', 'mine', 'index', 'show', 'destroy_collaborator'));

    $this->filter('before', 'i_am_owner')->only(array('destroy_collaborator'));
  }

  public function action_new() {
    $view = View::make('projects.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $project = new Project(Input::get('project'));
    $dt = new \DateTime();
    $project->proposals_due_at = $dt->modify('+1 month')->setTime(23,59,59);

    if ($project->validator()->passes()) {
      $project->save();
      $project->officers()->attach(Auth::officer()->id, array('owner' => true));
      return Redirect::to_route('project_template', array($project->id));
    } else {
      Session::flash('errors', $project->validator()->errors->all());
      return Redirect::to_route('new_projects')->with_input();
    }
  }

  public function action_template() {
    $view = View::make('projects.template');
    $view->project = Config::get('project');
    $view->templates = $view->project->available_templates()->take(3)->get();
    $view->more_templates_count = $view->project->available_templates()->count() - 3;
    if ($view->more_templates_count <= 0) $view->more_templates_count = false;
    $this->layout->content = $view;
  }

  public function action_template_post() {
    // Forking template
    $project = Config::get('project');
    $template = Config::get('template');
    $project->fork_from($template);
    return Redirect::to_route('project_background', array($project->id));
  }

  public function action_more_templates() {
    $project = Config::get('project');
    // @todo this will break once we have more than 100 templates.
    $templates = $project->available_templates()->take(100)->skip(3)->get();

    return Response::json(array('status' => 'success',
                                'html' => View::make('projects.partials.template_lis')
                                              ->with('templates', $templates)
                                              ->with('project', $project)
                                              ->render() ));
  }

  public function action_background() {
    $view = View::make('projects.background');
    $view->project = Config::get('project');
    $this->layout->content = $view;

    $view->project->save_progress('project_background');
  }

  public function action_background_post() {
    $project = Config::get('project');
    $project->fill(Input::get('project'));
    $project->save();
    return Redirect::to_route('project_sections', array($project->id));
  }

  public function action_sections_remove($project_id, $section_id) {
    $project = Config::get('project');
    $project->remove_section($section_id);

    if (Input::get('requested_html') == "sections_for_editing") {
      return Response::json(array('status' => 'success',
                                  'sections_for_editing_html' => View::make('projects.partials.sections_for_editing')
                                                                  ->with('project', $project)
                                                                  ->render()));

    } elseif (Input::get('requested_html') == "selected_sections") {
      return Response::json(array('status' => 'success',
                                  'selected_sections_html' => View::make('projects.partials.selected_sections')
                                                                  ->with('project', $project)
                                                                  ->render()));

    }

  }

  public function action_sections_add($project_id, $section_id) {
    $project = Config::get('project');
    $project->add_section($section_id);

    return Response::json(array('status' => 'success',
                                'sections_for_editing_html' => View::make('projects.partials.sections_for_editing')
                                                                ->with('project', $project)
                                                                ->render() ));
  }

  public function action_sections() {
    $view = View::make('projects.sections');
    $view->project = Config::get('project');
    $view->available_sections = $view->project->available_sections()->order_by('times_used', 'desc')->take(20)->get();
    $this->layout->content = $view;

    $view->project->save_progress('project_sections');
  }

  // This is for adding a new section or updating the text of an existing one.
  public function action_sections_post() {
    $project = Config::get('project');
    $section_id = Input::get('section_id');
    $section_input = Input::get('project_section');

    if ($section_id) {
      // we're editing an existing section
      $section = ProjectSection::find($section_id);
      if ($section->can_edit_without_forking()){
        $section->fill($section_input);
        $section->times_used = 1;
        $section->created_by_project_id = $project->id;
        $section->save();
      } else {
        $new_section = $section->fork($project->id);
        $new_section->fill($section_input);
        $new_section->save();
        $project->replace_section($section->id, $new_section->id);
      }

    } else {
      // we're adding a new sction
      $section = new ProjectSection($section_input);
      $section->created_by_project_id = $project->id;
      $section->save();
      $project->add_section($section->id);
    }

    return Response::json(array('status' => 'success',
                                'sections_for_editing_html' => View::make('projects.partials.sections_for_editing')
                                                                   ->with('project', $project)
                                                                   ->render() ));
  }

  public function action_sections_reorder() {
    $project = Config::get('project');

    $new_order = array_map(function($n){
      return intval($n);
    }, Input::get('sections'));

    $project->reorder_sections_to($new_order);
    return Response::json(array('status' => 'success'));
  }

  public function action_blanks() {
    $view = View::make('projects.blanks');
    $view->project = Config::get('project');
    $this->layout->content = $view;

    $view->project->save_progress('project_blanks');
  }

  public function action_blanks_post() {
    $project = Config::get('project');
    $project->variables = Input::get('variables');
    $project->save();
    return Redirect::to_route('project_timeline', array($project->id));
  }

  public function action_timeline() {
    $view = View::make('projects.timeline');
    $project = Config::get('project');

    // if this step is not yet completed, try to create some
    // deliverables from the project's SOW sections
    if ($project->sow_progress < 5) $project->create_deliverables_from_sow_sections();

    $view->project = $project;
    $view->deliverables = $view->project->deliverables ?: array();
    $this->layout->content = $view;

    $view->project->save_progress('project_timeline');
  }

  public function action_timeline_post() {
    $project = Config::get('project');
    $input_deliverables = Input::get('deliverables');
    $input_deliverable_dates = Input::get('deliverable_dates');

    $deliverables = array();
    $i = 0;
    foreach($input_deliverables as $deliverable) {
      if ($deliverable && trim($deliverable) != "") $deliverables[$deliverable] = $input_deliverable_dates[$i];
      $i++;
    }

    $project->deliverables = $deliverables;
    $project->save();
    return Redirect::to_route('project_review', array($project->id));
  }

  public function action_review() {
    $view = View::make('projects.review');
    $view->project = Config::get('project');
    $this->layout->content = $view;

    $view->project->save_progress('project_review');
  }

  public function action_show() {
    $view = View::make('projects.show');
    $view->project = Config::get('project');
    $this->layout->content = $view;
    if (Auth::user()) Auth::user()->view_notification_payload('project', $view->project->id, "read");
  }

  public function action_update() {
    $project = Config::get('project');
    $project->fill($project_input = Input::get('project'));
    $project->proposals_due_at = $project_input["proposals_due_at"] . " 23:59:59";

    if ($project->validator()->passes()) {
      $project->save();
      return Redirect::to_route('project_admin', array($project->id));
    } else {
      Session::flash('errors', $project->validator()->errors->all());
      return Redirect::to_route('project_admin', array($project->id))->with_input();
    }
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

  public function action_toggle_public() {
    $project = Config::get('project');
    $project->toggle_public();
    return Redirect::to(Input::get('redirect'));
  }

  public function action_index() {
    $view = View::make('projects.index');
    $view->projects = Project::open_projects()->get();
    $this->layout->content = $view;
  }

  public function action_search_available_sections() {
    $project = Config::get('project');
    $query = Input::get('query');
    $available_sections = $project->available_sections()
                                  ->where(function($q)use($query){
                                    $q->where('section_category', 'LIKE', '%'.$query.'%');
                                    $q->or_where('title', 'LIKE', '%'.$query.'%');
                                    $q->or_where('body', 'LIKE', '%'.$query.'%');
                                  })
                                  ->order_by('times_used', 'desc')
                                  ->take(20)
                                  ->get();

    return Response::json(array('status' => 'success',
                                'available_sections_tbody_html' => View::make('projects.partials.available_sections_tbody')
                                                                       ->with('project', $project)
                                                                       ->with('available_sections', $available_sections)
                                                                       ->render() ));
  }

  public function action_get_collaborators() {
    $project = Config::get('project');
    $collaborators = array();
    foreach($project->officers()->get() as $officer) {
      $collaborators[] = $officer->to_array();
    }
    return Response::json($collaborators);
  }

  public function action_add_collaborator() {
    $project = Config::get('project');
    $input = Input::json();
    $email = $input->User->email;
    $user = User::where_email($email)->first();

    if (!$user) {
      $user = User::new_officer_from_invite($email, Auth::user(), $project);
      if (!$user) return Response::make('400', '400');
      $send_email = false;
    } else {
      $send_email = true;
    }

    if ($user->officer->collaborates_on($project->id)) return Response::json(array("status" => "already exists"));

    $project->officers()->attach($user->officer->id);

    Notification::send("ProjectCollaboratorAdded", array("project" => $project,
                                                         "officer" => $user->officer,
                                                         "actor_id" => Auth::user()->id), $send_email);

    return Response::json($user->officer->to_array());
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

    if (!$attributes) {
      Helper::flash_errors("Couldn't find that contract on FBO.");
      return false;
    }

    // Check to make sure the info on FBO matches the info we have, unless
    // we're in the local (dev) environment.
    if (!Request::is_env('local')) {
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
    }

    if (!Auth::officer()->is_verified_contracting_officer()) {
      Auth::officer()->verify_with_solnbr($attributes["solnbr"]);
    }

    $project = Config::get('project');
    $project->fbo_solnbr = $attributes["solnbr"];

    if ($due_at = strtotime($attributes["response_date"])) {
      $project->proposals_due_at = date_timestamp_set(new \DateTime(), $due_at);
    }

    $project->save();

    // They posted it, make it public!
    if (!$project->public)
      $project->toggle_public();

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

  $route = $project->current_sow_composer_route_name();

  return Redirect::to_route($route, array($project->id));
});

Route::filter('template_exists_and_is_forkable', function(){
  $project = Config::get('project');
  $id = Request::$route->parameters[1];
  $template = Project::where_id($id)
                     ->where_public(true)
                     ->where_project_type_id($project->project_type_id)
                     ->first();

  if (!$template) return Redirect::to_route('project_background', array($project->id));
  Config::set('template', $template);
});

Route::filter('i_am_collaborator', function() { // also allowed if user is SUPER ADMIN
  $project = Config::get('project');
  if (!$project->is_mine() && !Auth::officer()->is_role_or_higher(Officer::ROLE_SUPER_ADMIN)) return Redirect::to('/');
});

Route::filter('i_am_owner', function() {
  $project = Config::get('project');
  if (!$project->i_am_owner()) return Redirect::to('/');
});
