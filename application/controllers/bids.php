<?php

class Bids_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'vendor_only')->only(array('new', 'create'));
    $this->filter('before', 'contract_exists')->only(array('new', 'create'));
    $this->filter('before', 'bid_not_already_made')->only(array('new', 'create'));
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

}

Route::filter('contract_exists', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if (!$contract) return Redirect::to('/');
  Config::set('contract', $contract);
});

Route::filter('bid_not_already_made', function() {
  $contract = Config::get('contract');
  $bid = Bid::where('vendor_id', '=', Auth::user()->vendor->id)
            ->where('contract_id', '=', $contract->id)
            ->first();

  if ($bid) {
    Session::flash('notice', 'Sorry, but you already placed a bid on this contract.');
    return Redirect::to_route('contract', array($contract->id));
  }
});