<?php

class Vendors_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'no_auth')->only(array('new', 'create'));
  }

  public function action_new() {
    $view = View::make('vendors.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $user = new User(Input::get('user'));
    $vendor = new Vendor(Input::get('vendor'));

    if ($user->validator()->passes() && $vendor->validator()->passes()) {
      $user->save();
      $user->vendor()->insert($vendor);
      $services = Input::get('services') ? array_keys(Input::get('services')) : array();
      $user->vendor->services()->sync($services);
      Auth::login($user->id);
      return Redirect::to('/');
    } else {
      Session::flash('errors', array_merge($user->validator()->errors->all(), $vendor->validator()->errors->all()));
      return $this->action_new();
    }
  }

  public function action_index() {
    $view = View::make('vendors.index');
    $page = intval(Input::get('page') ?: 1);
    $view->vendors = Vendor::skip(($page - 1) * 10)->take(10)->get();
    $this->layout->content = $view;
  }

}