<?php

class Bids_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'vendor_only')->only(array('new', 'create', 'mine', 'destroy'));

    $this->filter('before', 'project_exists')->except(array('mine'));

    $this->filter('before', 'i_am_collaborator')->only(array('review', 'star', 'dismiss'));

    $this->filter('before', 'bid_exists')->only(array('show', 'star', 'dismiss', 'destroy'));

    $this->filter('before', 'i_am_collaborator_or_bid_vendor')->only(array('show'));

    $this->filter('before', 'i_am_bid_vendor')->only(array('destroy'));

    $this->filter('before', 'i_have_not_already_bid')->only(array('new', 'create'));

  }

  public function action_review() {
    $view = View::make('bids.review');
    $view->project = Config::get('project');
    $view->open_bids = $view->project->open_bids()->get();
    $view->dismissed_bids = $view->project->dismissed_bids()->get();
    $this->layout->content = $view;
  }

  public function action_show() {
    $view = View::make('bids.show');
    $view->project = Config::get('project');
    $view->bid = Config::get('bid');
    $this->layout->content = $view;
    Auth::user()->view_notification_payload('bid', $view->bid->id);
  }

  public function action_new() {
    $view = View::make('bids.new');
    $view->project = Config::get('project');
    $this->layout->content = $view;
  }

  public function action_create() {
    $project = Config::get('project');
    $bid = $project->my_current_bid_draft() ?: new Bid();
    $bid->vendor_id = Auth::user()->vendor->id;
    $bid->project_id = $project->id;
    $bid->fill(Input::get('bid'));

    $prices = array();
    $i = 0;
    $deliverable_prices = Input::get('deliverable_prices');
    foreach (Input::get('deliverable_names') as $deliverable_name) {
      if (trim($deliverable_name) !== "") {
        $prices[$deliverable_name] = $deliverable_prices[$i];
      }
      $i++;
    }
    $bid->prices = $prices;

    if (Input::get('submit_now') === 'true') {
      if ($bid->validator()->passes()) {
        $bid->submit();
        Session::flash('notice', 'Thanks for submitting your bid.');
        return Redirect::to_route('bid', array($project->id, $bid->id));
      } else {
        Session::flash('errors', $bid->validator()->errors->all());
        return Redirect::to_route('new_bids', array($project->id, $bid->id))->with_input();
      }
    } else {
      $bid->save();
      return Response::json(array("status" => "success"));
    }

  }

  public function action_star() {
    $bid = Config::get('bid');
    $bid->starred = Input::get('starred');
    $bid->save();
    return Response::json(array("status" => "success", "starred" => $bid->starred));
  }

  // @todo only COs can dismiss bids!
  public function action_dismiss() {
    $bid = Config::get('bid');

    // if ($bid->dismissed()) return Response::json(array("status" => "already dismissed"));
    // we can prevent them from doing this if we want, but i don't see why not.

    if ($bid->dismissed()) {
      $bid->undismiss();
    } else {
      $bid->dismiss(Input::get('reason'), Input::get('explanation'));
    }
    return Response::json(array("status" => "success",
                                "dismissed" => $bid->dismissed(),
                                "html" => View::make("bids.partials.bid_for_review")->with('bid', $bid)->render()));
  }

  public function action_mine() {
    $view = View::make('bids.mine');
    $view->bids = Bid::where_vendor_id(Auth::vendor()->id)->get();
    $this->layout->content = $view;
  }


  public function action_destroy() {
    $project = Config::get('project');
    $bid = Config::get('bid');
    $bid->deleted_by_vendor = true;
    $bid->save();
    return Redirect::to_route('project', array($project->id));
  }

  // public function action_sf1449() {

  //   $contract = Config::get('contract');
  //   $bid = Config::get('bid');

  //   $query = http_build_query(array('pdf' => 'http://www.acq.osd.mil/dpap/ccap/cc/jcchb/Files/FormsPubsRegs/forms/sf1449_e.pdf',
  //                                   'solicitationnumber' => $contract->fbo_solnbr,
  //                                   'solicitationdate' => $contract->posted_at,
  //                                   'contactname' => $contract->officer->name,
  //                                   'contactphone' => $contract->officer->phone,
  //                                   'offerduedate' => $contract->proposals_due_at,
  //                                   'contractoraddress' => $bid->vendor->company_name."\n".
  //                                                          $bid->vendor->address."\n".
  //                                                          $bid->vendor->city.", ".$bid->vendor->state." ".$bid->vendor->zip,
  //                                   'schedule1' => 'SEE ATTACHED'));

  //   $contextData = array('method' => 'POST',
  //                        'header' => "Connection: close\r\n".
  //                        "Content-Type: "."application/x-www-form-urlencoded"."\r\n",
  //                        "Content-Length: ".strlen($query)."\r\n",
  //                        'content'=> $query);

  //   $context = stream_context_create(array('http' => $contextData));
  //   $contents = @file_get_contents('http://pdf-filler.heroku.com/fill', false, $context);

  //   if ($contents) {
  //     return Response::make($contents)
  //                    ->header('Content-Type', 'application/pdf');
  //   } else {
  //     return Response::error('404');
  //   }
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

Route::filter('bid_exists', function() {
  $id = Request::$route->parameters[1];
  $bid = Bid::find($id);
  if (!$bid) return Redirect::to('/');
  Config::set('bid', $bid);
});

Route::filter('i_am_collaborator_or_bid_vendor', function() {
  $bid = Config::get('bid');
  $project = Config::get('project');
  if (!$bid->is_mine() && !$project->is_mine()) return Redirect::to('/');
});

Route::filter('i_am_bid_vendor', function() {
  $bid = Config::get('bid');
  $project = Config::get('project');
  if (!$bid->is_mine()) return Redirect::to('/');
});

Route::filter('i_have_not_already_bid', function() {
  $project = Config::get('project');
  $bid = $project->current_bid_from(Auth::vendor());

  if ($bid) {
    Session::flash('notice', 'Sorry, but you already placed a bid on this project.');
    return Redirect::to_route('project', array($project->id));
  }
});

// Route::filter('contract_exists', function() {
//   $id = Request::$route->parameters[0];
//   $contract = Contract::find($id);
//   if (!$contract) return Redirect::to('/');
//   Config::set('contract', $contract);
// });

// Route::filter('contract_is_still_open_for_bids', function() {
//   $contract = Config::get('contract');
//   if (strtotime($contract->proposals_due_at) < time()) {
//     return Redirect::to('/contracts')->with('errors', array('Sorry, bids for that contract were already due.'));
//   }
// });

// Route::filter('bid_exists_and_is_not_only_a_draft', function() {
//   $id = Request::$route->parameters[1];
//   $bid = Bid::where_not_null('submitted_at')->where_id($id)->first();
//   if (!$bid) return Redirect::to('/');
//   Config::set('bid', $bid);
// });

// Route::filter('allowed_to_view', function() {
//   $bid = Config::get('bid');
//   $contract = Config::get('contract');
//   if (Auth::user()->officer) {
//     if ($contract->officer_id != Auth::user()->officer->id) return Redirect::to('/');
//   } else {
//     if ($bid->vendor_id != Auth::user()->vendor->id) return Redirect::to('/');
//   }
// });

// Route::filter('allowed_to_destroy', function() {
//   $bid = Config::get('bid');
//   if ($bid->vendor_id != Auth::user()->vendor->id) return Redirect::to('/');
// });

// Route::filter('allowed_to_review', function() {
//   $contract = Config::get('contract');
//   if ($contract->officer_id != Auth::user()->officer->id && !Auth::user()->officer->collaborates_on($contract->id))
//     return Redirect::to('/');
// });

// Route::filter('allowed_to_dismiss', function() {
//   $contract = Config::get('contract');
//   if ($contract->officer_id != Auth::user()->officer->id)
//     return Redirect::to('/');
// });

