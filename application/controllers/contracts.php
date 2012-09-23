<?php

class Contracts_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only')->only(array('new', 'create'));
  }


  public function action_new() {
    $view = View::make('contracts.new');
    $this->layout->content = $view;
  }

  public function action_create() {
    // make it
  }

}