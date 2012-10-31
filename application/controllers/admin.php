<?php

class Admin_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only');

    $this->filter('before', 'admin_only');

    $this->filter('before', 'super_admin_only')->only(array('project_sections'));
  }

  public function action_index() {
    $view = View::make('admin.index');
    $this->layout->content = $view;
  }

  public function action_project_sections() {
    $view = View::make('admin.project_sections');
    $view->project_sections = ProjectSection::paginate(10);
    $this->layout->content = $view;
  }

  public function action_project_sections_toggle_public($id) {
    $section = ProjectSection::find($id);
    $section->public = $section->public == 1 ? 0 : 1;
    $section->save();
    return Redirect::back();
  }

  public function action_templates() {
    $view = View::make('admin.templates');
    $view->templates = Project::all_available_templates()->paginate(10);
    $this->layout->content = $view;
  }

  public function action_officers() {
    $view = View::make('admin.officers');
    $view->officers = Officer::with('user')->order_by('name')->paginate(10);
    $this->layout->content = $view;
  }

  public function action_verify_contracting_officer($id) {
    $officer = Officer::find($id);
    if ($officer->role == Officer::ROLE_PROGRAM_OFFICER) {
      $officer->role = Officer::ROLE_CONTRACTING_OFFICER;
      $officer->save();
    }
    return Redirect::back();
  }

  public function action_template_toggle_recommended($project_id) {
    $project = Project::find($project_id);
    $project->recommended = $project->recommended == 1 ? 0 : 1;
    $project->save();
    return Redirect::back();
  }

}

Route::filter('super_admin_only', function() {
  if (!Auth::officer()->is_role_or_higher(Officer::ROLE_SUPER_ADMIN))
    return Redirect::to('/');
});

Route::filter('admin_only', function() {
  if (!Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN))
    return Redirect::to('/');
});
