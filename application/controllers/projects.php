<?php

class Projects_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only')->except(array('show', 'index', 'rss'));

    $this->filter('before', 'project_exists')->except(array('new', 'create', 'mine', 'index', 'rss'));

    $this->filter('before', 'project_posted')->only(array('show'));

    $this->filter('before', 'template_exists_and_is_forkable')->only('template_post');

    $this->filter('before', 'i_am_collaborator')->except(array('new', 'create', 'mine', 'index', 'show', 'destroy_collaborator', 'rss'));

    $this->filter('before', 'i_am_owner')->only(array('destroy_collaborator'));
  }

  public function action_rss($input_format) {
    Log::info($input_format);

    if ($input_format == "rss") {
      $format = "rss20";
      $content_type = "application/rss+xml; charset=ISO-8859-1";
    } elseif ($input_format == "atom") {
      $format = "atom";
      $content_type = "application/atom+xml";
    } else {
      return Response::error('404');
    }

    $feed = Feed::make();

    $feed->author('The RFP-EZ Team')
         ->pubdate(time())
         ->ttl(60)
         ->title('Government Contracting Opportunities on RFP-EZ')
         ->description('Government Contracting Opportunities on RFP-EZ')
         ->permalink(route('project_rss', 'rss20'))
         ->baseurl(URL::home());

    foreach (Project::open_projects()->take(20)->order_by('posted_to_fbo_at', 'desc')->get() as $project) {
      $feed->entry()->published($project->posted_to_fbo_at)
                    ->description()->add('html', $project->background)->up()
                    ->title($project->title)
                    ->permalink(route('project', $project->id));
    }

    Config::set("application.profiler", false);
    return Response::make($feed->send($format), 200, array('Content-type' => $content_type));

  }

  public function action_new() {
    $view = View::make('projects.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $project_input = Input::get('project');

    if ($project_input["project_type_id"] == "Other") {

      if (!Input::get('new_project_type_name')) {
        Session::flash('errors', array(__("r.flashes.new_project_no_project_type")));
        return Redirect::to_route('new_projects')->with_input();

      } elseif ($existing_project_type = ProjectType::where_name(Input::get('new_project_type_name'))->first()) {
        $project_input["project_type_id"] = $existing_project_type->id;

      } else {
        $project_type = new ProjectType(array('name' => Input::get('new_project_type_name')));
        $project_type->save();
        $project_input["project_type_id"] = $project_type->id;
      }

    }

    $project = new Project($project_input);

    $dt = new \DateTime($project_input["proposals_due_at"] . " 23:59:59", new DateTimeZone('America/New_York'));
    // if user doesn't specify a date, set it to 1 month from now
    if (!$project_input["proposals_due_at"]) $dt->modify('+1 month');
    $dt->setTimeZone(new DateTimeZone('UTC'));
    $project->proposals_due_at = $dt;

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

  public function action_edit() {
    $view = View::make('projects.edit');
    $view->project = Config::get('project');
    $this->layout->content = $view;
  }

  public function action_show() {
    $view = View::make('projects.show');
    $view->project = Config::get('project');
    $this->layout->content = $view;
    if (Auth::user()) Auth::user()->view_notification_payload('project', $view->project->id, "read");
  }

  public function action_update() {

    $project = Config::get('project');

    if (Request::ajax()) {
      // backbone update
      $json = Input::json(true);

      if (Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)) {
        $project->recommended = $json["recommended"];
        $project->public = $json["public"];
      }

      $project->save();

      return Response::json($project->to_array());

    } else {
      $project_input = Input::get('project');

      $project->title = $project_input["title"];
      $project->agency = $project_input["agency"];
      $project->office = $project_input["office"];
      $project->price_type = $project_input["price_type"];

      if ($project_input["proposals_due_at"]) {
        $dt = new \DateTime($project_input["proposals_due_at"] . " 23:59:59", new DateTimeZone('America/New_York'));
        $dt->setTimeZone(new DateTimeZone('UTC'));
        $project->proposals_due_at = $dt;
      }

      if ($project_input["question_period_over_at"]) {
        $dt = new \DateTime($project_input["question_period_over_at"] . " 23:59:59", new DateTimeZone('America/New_York'));
        $dt->setTimeZone(new DateTimeZone('UTC'));
        $project->question_period_over_at = $dt;
      } else {
        $project->question_period_over_at = null;
      }

      if ($project->validator()->passes()) {
        $project->save();
        return Redirect::to_route('project_admin', array($project->id));
      } else {
        Session::flash('errors', $project->validator()->errors->all());
        return Redirect::to_route('project_admin', array($project->id))->with_input();
      }
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
    $view->collaborators_json = eloquent_to_json($view->project->officers()->get());
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
    $project = Config::get('project');

    if (!Auth::officer()->is_role_or_higher(Officer::ROLE_CONTRACTING_OFFICER)) {
      // @todo add instructions for contacting admin to get verified
      Helper::flash_errors("Sorry, you haven't been verified as a contracting officer on RFP-EZ.");
      return Redirect::to_route('project_post_on_fbo', array($project->id));
    }

    $project->posted_to_fbo_at = new \DateTime;
    $project->save();

    // They posted it, make it public!
    if (!$project->public)
      $project->toggle_public();

    return Redirect::to_route('project', array($project->id));
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

  return Redirect::to_route('edit_project', array($project->id));
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

Route::filter('i_am_collaborator', function() { // also allowed if user is ADMIN
  $project = Config::get('project');
  if (!$project->is_mine() && !Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)) return Redirect::to('/');
});

Route::filter('i_am_owner', function() {
  $project = Config::get('project');
  if (!$project->i_am_owner()) return Redirect::to('/');
});
