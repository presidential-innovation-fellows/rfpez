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
    //$date->setTimestamp(1358488800); # 1/18/2013
    $date->setTimestamp(1355097600); # 12/10/2012

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

    $total_price_for_all = 0;
    $total_bids_in_all = 0;
    $total_prices = array();
    foreach (Bid::where_not_null('submitted_at')->get() as $bid) {
      if (!isset($total_prices[$bid->project_id])) {
        $total_prices[$bid->project_id] = array(
          'num_bids' => 0,
          'total_price' => 0,
        );
      }

      $total_prices[$bid->project_id]['num_bids']++;
      $total_prices[$bid->project_id]['total_price'] += $bid->total_price_integer();

      $total_bids_in_all++;
      $total_price_for_all += $bid->total_price_integer();
    }

    $avg_prices = array();
    foreach (Project::all() as $project) {
      array_push($avg_prices, array(
        'project_id' => $project->id,
        'project_title' => $project->title,
        'avg_price' => isset($total_prices[$project->id]) ? $total_prices[$project->id]['total_price'] / $total_prices[$project->id]['num_bids'] : 0
      ));
    }

    $avg_price_total = $total_price_for_all / $total_bids_in_all;


    $view->total_signups = $num_signups;
    $view->total_new_to_contracting = $num_new;
    $view->signups_per_day = $signups_per_day;
    $view->new_to_contracting = $new_to_contracting;
    $view->bids_per_project = $bids_per_project;
    $view->avg_bids_per_project = $avg_bids_per_project;
    $view->avg_prices = $avg_prices;
    $view->avg_price_total = $avg_price_total;

    $this->layout->content = $view;
  }

}

Route::filter('admin_only', function() {
  if (!Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN))
    return Redirect::to('/');
});

