<?php

class Home_Controller extends Base_Controller {

  public function action_index() {
    if (Auth::check()) {
      if (Auth::user()->officer) {
        return Redirect::to_route('my_contracts');
      } else {
        return Redirect::to_route('contracts');
      }
      // When we have something better...
      //$view = View::make('home.index_signed_in');
    } else {
      $view = View::make('home.index_signed_out');
    }
    $this->layout->content = $view;
  }

}