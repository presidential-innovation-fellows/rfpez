<?php

class Reports_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only');

    $this->filter('before', 'admin_only');

  }

  public function action_index() {
    $view = View::make('reports.index');

    $all_days = array();
    $date = new \DateTime();
    $date->setTimestamp(1358488800); # 1/18/2013

    while ($date < new \DateTime()) {
      array_push($all_days, $date->format("Y-m-d"));
      $date = $date->modify("+1 day");
    }

    $signups = array();
    foreach (DB::query("SELECT COUNT(*) as count, DATE(created_at) as date FROM vendors GROUP BY DATE(created_at)") as $result) {
      $signups[$result->date] = $result->count;
    }

    $signups_per_day = array();
    foreach ($all_days as $day) {
      $signups_per_day[$day] = @$signups[$day] ?: 0;
    }

    $signups = array();
    foreach (DB::query("SELECT DATE(created_at) as date, duns FROM vendors") as $result) {
      if (!isset($signups[$result->date])) $signups[$result->date] = array('new' => 0, 'old' => 0);

      if ($result->duns) {
        $signups[$result->date]['old']++;
      } else {
        $signups[$result->date]['new']++;
      }
    }

    $num_signups = 0;
    $num_new = 0;
    $new_to_contracting = array();
    foreach ($all_days as $day) {
      if (!isset($signups[$day])) continue;

      $num_signups += $signups[$day]['new'];
      $num_signups += $signups[$day]['old'];
      $num_new += $signups[$day]['new'];

      $new_to_contracting[$day] = $num_new / $num_signups;
    }

    $total_projects = 0;
    $total_bids = 0;
    $bids_per_project = array();
    foreach (DB::query("SELECT id, title, (SELECT COUNT(*) from bids where project_id = projects.id) as bids from projects") as $result) {
      array_push($bids_per_project, array(
        'project_id' => $result->id,
        'project_title' => $result->title,
        'num_bids' => $result->bids
      ));
      $total_projects++;
      $total_bids += $result->bids;
    }

    $avg_bids_per_project = $total_bids / $total_projects;



    $view->signups_per_day = $signups_per_day;
    $view->new_to_contracting = $new_to_contracting;
    $view->bids_per_project = $bids_per_project;
    $view->avg_bids_per_project = $avg_bids_per_project;

    $this->layout->content = $view;
  }

}

Route::filter('admin_only', function() {
  if (!Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN))
    return Redirect::to('/');
});

