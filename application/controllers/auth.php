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
    $credentials = array('username' => Input::get('email'),
                         'password' => Input::get('password'),
                         'remember' => Input::has('remember') ? true : false);

    if (Auth::attempt($credentials)) {
      Auth::user()->track_signin();

      if (Input::has('modal')) return Redirect::back();
      if ($url = Input::get('redirect_to')) return Redirect::to($url);
      return Redirect::to('/');
    } else {
      return Redirect::to_route('signin')
                     ->with('errors', array('Login incorrect.'))
                     ->with('redirect_to', Request::referrer())
                     ->with_input();
    }
  }

  public function action_delete() {
    Auth::logout();
    return Redirect::to('/');
  }

}