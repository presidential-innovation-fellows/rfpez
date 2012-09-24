<?php

class Officers_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'no_auth')->only(array('new', 'create'));
  }

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
      Session::flash('notice', 'Please check your email for a link to finish signup.');
      return Redirect::to('/');
    } else {
      Session::flash('errors', array_merge($user->validator(false)->errors->all(), $officer->validator()->errors->all()));
      return $this->action_new();
    }
  }

}