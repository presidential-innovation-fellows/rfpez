<?php

class Bids_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'auth')->only(array('show'));
    $this->filter('before', 'vendor_only')->only(array('new', 'create', 'destroy'));
    $this->filter('before', 'contract_exists')->only(array('new', 'create', 'show', 'destroy'));
    $this->filter('before', 'bid_not_already_made')->only(array('new', 'create'));
    $this->filter('before', 'bid_exists')->only(array('show', 'destroy'));
    $this->filter('before', 'allowed_to_view')->only(array('show'));
    $this->filter('before', 'allowed_to_destroy')->only(array('destroy'));
  }

  public function action_new() {
    $view = View::make('bids.new');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
  }

  public function action_create() {
    $contract = Config::get('contract');
    $bid = new Bid();
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

    $bid->save();

    Session::flash('notice', 'Thanks for submitting your bid.');
    return Redirect::to_route('contract', array($contract->id));
  }

  public function action_show() {
    $view = View::make('bids.show');
    $view->contract = Config::get('contract');
    $view->bid = Config::get('bid');
    $this->layout->content = $view;
  }

  public function action_destroy() {
    $contract = Config::get('contract');
    $bid = Config::get('bid');
    $bid->dismiss('Deleted by vendor');
    return Redirect::to_route('contract', array($contract->id));
  }

}

Route::filter('contract_exists', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if (!$contract) return Redirect::to('/');
  Config::set('contract', $contract);
});

Route::filter('bid_exists', function() {
  $id = Request::$route->parameters[1];
  $bid = Bid::find($id);
  if (!$bid) return Redirect::to('/');
  Config::set('bid', $bid);
});

Route::filter('allowed_to_view', function() {
  $bid = Config::get('bid');
  $contract = Config::get('contract');
  if (Auth::user()->is_officer()) {
    if ($contract->officer_id != Auth::user()->officer->id) return Redirect::to('/');
  } else {
    if ($bid->vendor_id != Auth::user()->vendor->id) return Redirect::to('/');
  }
});

Route::filter('allowed_to_destroy', function() {
  $bid = Config::get('bid');
  if ($bid->vendor_id != Auth::user()->vendor->id) return Redirect::to('/');
});

Route::filter('bid_not_already_made', function() {
  $contract = Config::get('contract');
  $bid = Bid::where('vendor_id', '=', Auth::user()->vendor->id)
            ->where('contract_id', '=', $contract->id)
            ->where(function($query) {
              $query->where('dismissal_reason', '!=', 'Deleted by vendor');
              $query->or_where_null('dismissal_reason');
            })
            ->first();

  if ($bid) {
    Session::flash('notice', 'Sorry, but you already placed a bid on this contract.');
    return Redirect::to_route('contract', array($contract->id));
  }
});