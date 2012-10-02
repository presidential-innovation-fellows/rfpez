<?php

class Home_Controller extends Base_Controller {

  public function action_index() {
    if (Auth::check()) {
      $view = View::make('home.index_signed_in');
    } else {
      $view = View::make('home.index_signed_out');
    }
    $this->layout->content = $view;
  }

}