<?php

class Reports_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only');

    $this->filter('before', 'admin_only');

  }

  public function action_index() {
    $view = View::make('reports.index');
    $this->layout->content = $view;
  }

}

Route::filter('admin_only', function() {
  if (!Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN))
    return Redirect::to('/');
});

