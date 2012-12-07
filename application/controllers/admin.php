<?php

class Admin_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only');

    $this->filter('before', 'admin_only');

    $this->filter('before', 'super_admin_only')->only(array('project_sections', 'ban_officer', 'emails'));
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

  public function action_projects() {
    $view = View::make('admin.projects');
    $view->projects = Project::with('project_type')->paginate(10);
    $view->projects_json = eloquent_to_json($view->projects->results);
    $this->layout->content = $view;
  }

  public function action_officers() {
    $view = View::make('admin.officers');
    $view->officers = Officer::paginate(10);
    $view->officers_json = eloquent_to_json($view->officers->results);
    $this->layout->content = $view;
  }

  public function action_vendors() {
    $view = View::make('admin.vendors');
    $view->vendors = Vendor::paginate(10);
    $this->layout->content = $view;
  }

  public function action_ban_vendor($id) {
    $vendor = Vendor::find($id);
    $vendor->ban();
    return Redirect::back();
  }

  public function action_template_toggle_recommended($project_id) {
    $project = Project::find($project_id);
    $project->recommended = $project->recommended == 1 ? 0 : 1;
    $project->save();
    return Redirect::back();
  }

  public function action_emails() {
    $view = View::make('admin.emails');
    $view->vendor_emails = Vendor::join('users', 'user_id', '=', 'users.id')
                           ->where_null('banned_at')
                           ->where('send_emails', '=', true)
                           ->lists('email');
    $view->officer_emails = Officer::join('users', 'user_id', '=', 'users.id')
                           ->where_null('banned_at')
                           ->where('send_emails', '=', true)
                           ->lists('email');
    $this->layout->content = $view;
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
