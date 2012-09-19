<?php

class Vendors_Controller extends Base_Controller {

  public function action_new() {
    $view = View::make('vendors.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $user = new User(Input::get('user'));
    $vendor = new Vendor(Input::get('vendor'));
    $userValidator = $user->validator();
    $vendorValidator = $vendor->validator();

    if ($userValidator->valid() && $vendorValidator->valid()) {
      $user->save();
      $user->vendor()->insert($vendor);
      return 'saved';
    } else {
      return Redirect::to_route('new_vendors')->with_errors($userValidator->errors);
    }
  }

}