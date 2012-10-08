<?php

class Bids_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'auth')->only(array('show', 'sf1449'));
    $this->filter('before', 'officer_only')->only(array('review', 'dismiss', 'star'));
    $this->filter('before', 'vendor_only')->only(array('new', 'create', 'destroy', 'mine'));
    $this->filter('before', 'contract_exists')->except('mine');
    $this->filter('before', 'bid_not_already_made')->only(array('new', 'create'));
    $this->filter('before', 'bid_exists_and_is_not_only_a_draft')->only(array('show', 'destroy', 'dismiss', 'star', 'sf1449'));
    $this->filter('before', 'allowed_to_view')->only(array('show', 'sf1449'));
    $this->filter('before', 'allowed_to_destroy')->only(array('destroy'));
    $this->filter('before', 'allowed_to_review')->only(array('review', 'dismiss', 'star'));
  }

  public function action_new() {
    $view = View::make('bids.new');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
  }

  public function action_mine() {
    $view = View::make('bids.mine');
    $view->bids = Bid::where_vendor_id(Auth::user()->vendor->id)->get();
    $this->layout->content = $view;
  }

  public function action_review() {
    $view = View::make('bids.review');
    $view->contract = Config::get('contract');
    $view->open_bids = $view->contract->open_bids();
    $view->dismissed_bids = $view->contract->dismissed_bids();
    $this->layout->content = $view;
  }

  public function action_dismiss() {
    $contract = Config::get('contract');
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
                                "html" => View::make("partials.media.bid_for_review")->with('bid', $bid)->render()));
  }

  public function action_star() {
    $contract = Config::get('contract');
    $bid = Config::get('bid');
    $bid->starred = Input::get('starred');
    $bid->save();
    return Response::json(array("status" => "success", "starred" => $bid->starred));
  }

  public function action_create() {
    $contract = Config::get('contract');
    $bid = $contract->my_current_bid_draft() ?: new Bid();
    $bid->vendor_id = Auth::user()->vendor->id;
    $bid->contract_id = $contract->id;
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
        return Redirect::to_route('bid', array($contract->id, $bid->id));
      } else {
        Session::flash('errors', $bid->validator()->errors->all());
        return Redirect::to_route('new_bids', array($contract->id, $bid->id))->with_input();
      }
    } else {
      $bid->save();
      return Response::json(array("status" => "success"));
    }

  }

  public function action_show() {
    $view = View::make('bids.show');
    $view->contract = Config::get('contract');
    $view->bid = Config::get('bid');
    $this->layout->content = $view;
    Auth::user()->view_notification_payload('bid', $view->bid->id);
  }

  public function action_destroy() {
    $contract = Config::get('contract');
    $bid = Config::get('bid');
    $bid->deleted_by_vendor = true;
    $bid->save();
    return Redirect::to_route('contract', array($contract->id));
  }

  public function action_sf1449() {

    $contract = Config::get('contract');
    $bid = Config::get('bid');

    $query = http_build_query(array('pdf' => 'http://www.acq.osd.mil/dpap/ccap/cc/jcchb/Files/FormsPubsRegs/forms/sf1449_e.pdf',
                                    'solicitationnumber' => $contract->fbo_solnbr,
                                    'solicitationdate' => $contract->posted_at,
                                    'contactname' => $contract->officer->name,
                                    'contactphone' => $contract->officer->phone,
                                    'offerduedate' => $contract->proposals_due_at,
                                    'contractoraddress' => $bid->vendor->company_name."\n".
                                                           $bid->vendor->address."\n".
                                                           $bid->vendor->city.", ".$bid->vendor->state." ".$bid->vendor->zip,
                                    'schedule1' => 'SEE ATTACHED'));

    $contextData = array('method' => 'POST',
                         'header' => "Connection: close\r\n".
                         "Content-Type: "."application/x-www-form-urlencoded"."\r\n",
                         "Content-Length: ".strlen($query)."\r\n",
                         'content'=> $query);

    $context = stream_context_create(array('http' => $contextData));
    $contents = @file_get_contents('http://pdf-filler.heroku.com/fill', false, $context);

    if ($contents) {
      return Response::make($contents)
                     ->header('Content-Type', 'application/pdf');
    } else {
      return Response::error('404');
    }
  }

}

Route::filter('contract_exists', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if (!$contract) return Redirect::to('/');
  Config::set('contract', $contract);
});

Route::filter('bid_exists_and_is_not_only_a_draft', function() {
  $id = Request::$route->parameters[1];
  $bid = Bid::where_not_null('submitted_at')->where_id($id)->first();
  if (!$bid) return Redirect::to('/');
  Config::set('bid', $bid);
});

Route::filter('allowed_to_view', function() {
  $bid = Config::get('bid');
  $contract = Config::get('contract');
  if (Auth::user()->officer) {
    if ($contract->officer_id != Auth::user()->officer->id) return Redirect::to('/');
  } else {
    if ($bid->vendor_id != Auth::user()->vendor->id) return Redirect::to('/');
  }
});

Route::filter('allowed_to_destroy', function() {
  $bid = Config::get('bid');
  if ($bid->vendor_id != Auth::user()->vendor->id) return Redirect::to('/');
});

Route::filter('allowed_to_review', function() {
  $contract = Config::get('contract');
  if ($contract->officer_id != Auth::user()->officer->id) return Redirect::to('/');
});

Route::filter('bid_not_already_made', function() {
  $contract = Config::get('contract');
  $bid = $contract->current_bid_from(Auth::user()->vendor);

  if ($bid) {
    Session::flash('notice', 'Sorry, but you already placed a bid on this contract.');
    return Redirect::to_route('contract', array($contract->id));
  }
});