<?php

class Vendors_Controller extends Base_Controller {

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
      return 'saved';
    } else {
      Session::flash('errors', array_merge($user->validator(false)->errors->all(), $officer->validator()->errors->all()));
      return $this->action_new();
    }
  }

}