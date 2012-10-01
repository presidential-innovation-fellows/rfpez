<?php

class Users_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'has_valid_reset_password_token')->only(array('get_reset_password', 'post_reset_password'));

    $this->filter('before', 'no_auth')->only(array('get_forgot_password', 'post_forgot_password',
                                                   'get_reset_password', 'post_reset_password'));

    $this->filter('before', 'auth')->only(array('get_account', 'post_account'));
  }


  public function action_get_forgot_password() {
    $view = View::make('users.get_forgot_password');
    $this->layout->content = $view;
  }

  public function action_post_forgot_password() {
    $user = User::where_email(Input::get('email'))->first();
    if (!$user) {
      Session::flash('errors', array('User not found.'));
      return Redirect::to_route('forgot_password')->with_input();
    }
    $user->generate_reset_password_token();
    Session::flash('notice', 'reset token generated');
    return Redirect::to_route('signin');
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
      Auth::login($user->id);
      $user->track_signin();
      return Redirect::to('/');
    } else {
      Session::flash('errors', array('New password not valid.'));
      return Redirect::to_route('reset_password', array($user->reset_password_token));
    }
  }

  public function action_get_account() {
    $view = View::make('users.account');
    $this->layout->content = $view;
  }

  public function action_post_account() {

    if ($vendor = Auth::user()->vendor) {

      $vendor->fill(Input::get('vendor'));
      if ($vendor->validator()->passes()) {
        $services = Input::get('services') ? array_keys(Input::get('services')) : array();
        $vendor->services()->sync($services);
        $vendor->save();
        return Redirect::to_route('account');
      } else {
        Session::flash('errors', $vendor->validator()->errors->all());
        return Redirect::to_route('account')->with_input();
      }

    } else if ($officer = Auth::user()->officer) {
      $officer->fill(Input::get('officer'));
      if ($officer->validator()->passes()) {
        $officer->save();
        return Redirect::to_route('account');
      } else {
        Session::flash('errors', $officer->validator()->errors->all());
        return Redirect::to_route('account')->with_input();
      }

    }
  }

}

Route::filter('has_valid_reset_password_token', function() {
  $token = Request::$route->parameters[0];
  $user = User::where_reset_password_token($token)->first();
  if (!$user) return Redirect::to('/');
  Config::set('user', $user);
});
