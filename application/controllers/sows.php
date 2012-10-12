<?php

class Sows_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();


    $this->filter('before', 'officer_only')->except(array(/* ... */));

    $this->filter('before', 'project_exists')->except(array());

    $this->filter('before', 'i_am_collaborator')->except(array());

    $this->filter('before', 'sow_exists')->except(array());


    // $this->filter('before', 'collaborator_exists')->only(array('destroy_collaborator'));
  }

  public function action_background() {
    $view = View::make('sows.background');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  public function action_background_post() {
    $sow = Config::get('sow');
    $project = Config::get('project');
    $background_section = $sow->sow_sections()->where('section_type', '=', 'Background & Scope')
                                              ->first();

    if ($background_section) {
      $background_section->body = Input::get('body');
      $background_section->save();
    } else {
      SowSection::create(array('sow_id' => $sow->id,
                               'section_type' => 'Background & Scope',
                               'body' => Input::get('body')));
    }

    return Redirect::to(route('sow_section', array($project->id, rawurlencode($sow->first_template_section_type()))));
  }

  public function action_section($project_id, $section_type) {
    $view = View::make('sows.section');
    $view->sow = Config::get('sow');
    $view->section_type = $section_type;
    $view->sections = SowTemplateSection::where('sow_template_id', '=', $view->sow->based_on_sow_template_id)
                                        ->where('section_type', '=', $section_type)
                                        ->get();

    $view->custom_sections = $view->sow->sow_sections()->where('section_type', '=', $section_type)
                                                       ->where_null('based_on_sow_template_section_id')
                                                       ->get();
    $this->layout->content = $view;
  }

  public function action_section_post($project_id, $section_type) {
    $sow = Config::get('sow');
    $project = Config::get('project');

    foreach ($sow->sections($section_type) as $section) { $section->delete(); }

    if (Input::get('sections')) {
      $i = 0;
      $deliverable_dates = Input::get('deliverable_dates');
      foreach(Input::get('sections') as $sow_section_id) {
        $section = SowTemplateSection::find($sow_section_id);
        SowSection::create(array('sow_id' => $sow->id,
                                 'section_type' => $section->section_type,
                                 'based_on_sow_template_section_id' => $section->id,
                                 'display_order' => $i));

        if (count($deliverable_dates) > 0){
          $sow->add_duedate($section->title, $deliverable_dates[$i]);
        }

        ++$i;
      }
    }

    if (Input::get('custom_sections')) {
      $i = 0;
      $custom_deliverable_dates = Input::get('custom_deliverable_dates');
      foreach(Input::get('custom_sections') as $custom_section_name) {
        $custom_bodies = Input::get("custom_section_bodies");
        $body = $custom_bodies[$i];
        if (!$body) $body = "";
        SowSection::create(array('sow_id' => $sow->id,
                                 'section_type' => $section_type,
                                 'title' => $custom_section_name,
                                 'display_order' => $i,
                                 'body' => $body));

        if (count($custom_deliverable_dates) > 0) {
          $sow->add_duedate($custom_section_name, $custom_deliverable_dates[$i]);
        }
        ++$i;
      }
    }

    $sow->save();

    if ($next_section_type = $sow->template_section_type_after($section_type)) {
      return Redirect::to(route('sow_section', array($project->id, $next_section_type)));
    } else {
      return Redirect::to(route('sow_fillinblanks', array($project->id)));
    }
  }

  public function action_fillinblanks() {
    $view = View::make('sows.fillinblanks');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  public function action_fillinblanks_post() {
    $sow = Config::get('sow');
    $project = Config::get('project');

    if (Input::get('custom_sections')) {
      foreach(Input::get('custom_sections') as $key => $val) {
        $section = SowSection::find($key);
        $section->body = $val;
        $section->save();
      }
    }

    if (Input::get('variables')) {
      foreach(Input::get('variables') as $key => $val) {
        $sow->add_variable($key, $val);
      }
    }

    $sow->save();
    return Redirect::to(route('sow_editdoc', array($project->id)));
  }

  public function action_editdoc() {
    $view = View::make('sows.editdoc');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  public function action_editdoc_post() {
    $sow = Config::get('sow');
    $project = Config::get('project');
    $sow->body = Input::get('body');
    $sow->save();

    return Redirect::to(route('sow_review', array($project->id)));
  }

  public function action_review() {
    $view = View::make('sows.review');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  // public function action_doc() {
  //   $sow = Config::get('sow');
  //   return View::make('partials.doc')->with('sow', $sow);
  // }



  // public function action_mine() {
  //   $view = View::make('sows.mine');
  //   $this->layout->content = $view;
  // }


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

Route::filter('sow_exists', function() {
  $project = Config::get('project');
  $sow = $project->sow;
  if (!$sow) return Redirect::to('/');
  Config::set('sow', $sow);
});
