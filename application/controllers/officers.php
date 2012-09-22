<?php

class Officers_Controller extends Base_Controller {

  public function action_new() {
    $view = View::make('officers.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    $user = new User(Input::get('user'));
    $officer = new Officer(Input::get('officer'));

    if ($user->validator(false)->passes() && $officer->validator()->passes()) {
      $user->save();
      $user->officer()->insert($officer);
      $user->generate_reset_password_token();
      return 'saved';
    } else {
      Session::flash('errors', array_merge($user->validator(false)->errors->all(), $officer->validator()->errors->all()));
      return $this->action_new();
    }
  }

  public function action_get_forgot_password() {
    // display the password recovery form
  }

  public function action_post_forgot_password() {
    $user = User::where_email(Input::get('email'))->first();
    $user->generate_reset_password_token();
    return 'reset token generated';
  }

}