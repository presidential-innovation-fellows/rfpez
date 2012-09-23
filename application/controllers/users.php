<?php

class Users_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'has_valid_reset_password_token')->only(array('get_reset_password', 'post_reset_password'));
  }


  public function action_get_forgot_password() {
    $view = View::make('users.get_forgot_password');
    $this->layout->content = $view;
  }

  public function action_post_forgot_password() {
    $user = User::where_email(Input::get('email'))->first();
    if (!$user) {
      Session::flash('errors', array('User not found.'));
      return $this->action_get_forgot_password();
    }
    $user->generate_reset_password_token();
    return 'reset token generated';
  }

  public function action_get_reset_password() {
    $view = View::make('users.get_reset_password');
    $view->user = Config::get('user');
    $view->finish_signup = $view->user->sign_in_count == 0 ? true : false;
    $this->layout->content = $view;
  }

  public function action_post_reset_password() {
    $user = Config::get('user');

    if ($user->reset_password_to(Input::get('password'))) {
      Auth::login($user);
      return Redirect::to('/');
    } else {
      Session::flash('errors', array('New password not valid.'));
      return Redirect::to_route('reset_password', array($user->reset_password_token));
    }
  }

}

Route::filter('has_valid_reset_password_token', function() {
  $token = Request::$route->parameters[0];
  $user = User::where_reset_password_token($token)->first();
  if (!$user) return Redirect::to('/');
  Config::set('user', $user);
});
