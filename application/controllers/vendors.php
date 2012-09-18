<?php

class Vendors_Controller extends Base_Controller {

  public function action_new() {
    $view = View::make('vendors.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $vendor = new Vendor(Input::get('vendor'));
    $validator = $vendor->validator();

    if ($validator->fails()) {
      return Redirect::to_route('new_vendors')->with_errors($validator->errors);
    } else {
      $vendor->save();

      foreach (Input::get('services') as $key => $val) {
        $vendor->services()->attach($key);
      }

      return 'vendor saved';
    }
  }

}