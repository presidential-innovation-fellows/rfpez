<?php

class Sows_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'sow_exists')->except(array('mine', 'new', 'new_post'));

    $this->filter('after', 'doc')->only(array('doc'));

    $this->filter('before', 'collaborator_exists')->only(array('destroy_collaborator'));
  }

  public function action_add_collaborator() {
    $sow = Config::get('sow');
    $user = User::where_email(Input::get('email'))->first();
    if (!$user) return Response::json(array("status" => "error"));
    if ($user->id === Auth::user()->id) return Response::json(array("status" => "can't add yourself"));
    if ($user->officer->collaborates_on_sow($sow->id)) return Response::json(array("status" => "already exists"));

    $sow->collaborators()->attach($user->officer->id);
    Notification::send("SowCollaboratorAdded", array("sow" => $sow,
                                                     "officer" => $user->officer));

    return Response::json(array("status" => "success",
                                "html" => View::make("partials.media.sow_collaborator_tr")
                                              ->with('officer', $user->officer)
                                              ->with('sow', $sow)
                                              ->render() ));
  }

  public function action_destroy_collaborator() {
    $collaborator = Config::get('collaborator');
    $collaborator->delete();
    return Response::json(array("status" => "success"));
  }

  public function action_mine() {
    $view = View::make('sows.mine');
    $this->layout->content = $view;
  }

  public function action_new() {
    $view = View::make('sows.new');
    $this->layout->content = $view;
  }

  public function action_new_post() {
    $template = SowTemplate::find(Input::get('template_id'));
    $sow = Sow::create(array('title' => $template->title . " for " . Auth::user()->officer->agency,
                             'officer_id' => Auth::user()->officer->id,
                             'based_on_sow_template_id' => $template->id));

    return Redirect::to(route('sow_background', array($sow->id)));
  }

  public function action_background() {
    $view = View::make('sows.background');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  public function action_background_post() {
    $sow = Config::get('sow');
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

    return Redirect::to(route('sow_section', array($sow->id, rawurlencode($sow->first_template_section_type()))));
  }

  public function action_section($id, $section_type) {
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

  public function action_section_post($id, $section_type) {
    $sow = Config::get('sow');

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
      return Redirect::to(route('sow_section', array($sow->id, $next_section_type)));
    } else {
      return Redirect::to(route('sow_fillinblanks', array($sow->id)));
    }
  }

  public function action_fillinblanks() {
    $view = View::make('sows.fillinblanks');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  public function action_fillinblanks_post() {
    $sow = Config::get('sow');
    $sow->title = Input::get('title');
    $sow->body = false;

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
    return Redirect::to(route('sow_editdoc', array($sow->id)));
  }


  public function action_editdoc() {
    $view = View::make('sows.editdoc');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  public function action_editdoc_post() {
    $sow = Config::get('sow');
    $sow->body = Input::get('body');
    $sow->save();

    return Redirect::to(route('sow', array($sow->id)));
  }

  public function action_show() {
    $view = View::make('sows.show');
    $view->sow = Config::get('sow');
    $this->layout->content = $view;
  }

  public function action_doc() {
    $sow = Config::get('sow');
    return View::make('partials.doc')->with('sow', $sow);
  }

  public function action_print() {
    $view = View::make('sows.print');
    $view->sow = Config::get('sow');
    return $view;
  }

  public function action_view($read_only_uuid) {
    $view = View::make('sows.view');
    $view->sow = Sow::where('read_only_uuid', '=', $read_only_uuid)->first();
    $this->layout->content = $view;
  }

}

Route::filter('sow_exists', function() {
  $id = Request::$route->parameters[0];
  $sow = Sow::find($id);
  if (!$sow) return Redirect::to('/');
  Config::set('sow', $sow);
});

Route::filter('collaborator_exists', function() {
  $sow_id = Request::$route->parameters[0];
  $officer_id = Request::$route->parameters[1];
  $collaborator = SowCollaborator::where_sow_id($sow_id)
                                 ->where_officer_id($officer_id)
                                 ->first();
  if (!$collaborator) return Redirect::to('/');
  Config::set('collaborator', $collaborator);
});

Route::filter('doc', function($response) {
  $response->header('Content-type', 'application/vnd.ms-word');
  $response->header('Content-Disposition', 'attachment;Filename='.Config::get('sow')->title.'.doc');
});