<?php

class Vendors_Controller extends Base_Controller {

  /*
  |--------------------------------------------------------------------------
  | The Default Controller
  |--------------------------------------------------------------------------
  |
  | Instead of using RESTful routes and anonymous functions, you might wish
  | to use controllers to organize your application API. You'll love them.
  |
  | This controller responds to URIs beginning with "home", and it also
  | serves as the default controller for the application, meaning it
  | handles requests to the root of the application.
  |
  | You can respond to GET requests to "/home/profile" like so:
  |
  |   public function action_profile()
  |   {
  |     return "This is your profile!";
  |   }
  |
  | Any extra segments are passed to the method as parameters:
  |
  |   public function action_profile($id)
  |   {
  |     return "This is the profile for user {$id}.";
  |   }
  |
  */

  public function action_new() {
    $view = View::make('vendors.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $vendor = new Vendor(Input::get('vendor'));
    $validator = $vendor->validator();

    if ($validator->fails()){
      return Redirect::to_route('new_vendors')->with_errors($validator->errors);
    } else {
      $vendor->save();
      return 'vendor saved';
    }
  }

}