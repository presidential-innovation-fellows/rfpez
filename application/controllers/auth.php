<?php

class Auth_Controller extends Base_Controller {

  public function action_new() {
    $view = View::make('auth.signin');
    $this->layout->content = $view;
  }

  public function action_create() {
    // authenticate
  }

}