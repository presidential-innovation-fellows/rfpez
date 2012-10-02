<?php

class Validation_Controller extends Base_Controller {

  public function action_email() {
    $user_input = Input::get('user');
    $user = User::where_email($user_input["email"])->first();
    if ($user) {
      return Response::json("Sorry, that email address is already registered.");
    } else {
      return Response::json(true);
    }
  }

}