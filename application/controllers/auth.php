<?php

class Auth_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'no_auth')->only(array('new', 'create'));
    $this->filter('before', 'auth')->only(array('delete'));
  }


  public function action_new() {
    $view = View::make('auth.signin');
    $this->layout->content = $view;
  }

  public function action_create() {
    $credentials = array('username' => Input::get('email'), 'password' => Input::get('password'));

    if (Auth::attempt($credentials)) {
      Auth::user()->track_signin();
      return Redirect::to('/');
    } else {
      Session::flash('errors', array('Login incorrect.'));
      return Redirect::to_route('signin');
    }
  }

  public function action_delete() {
    Auth::logout();
    return Redirect::to('/');
  }

}