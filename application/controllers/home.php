<?php

class Home_Controller extends Base_Controller {

  public function action_index() {
    if (Auth::check()) {
      if (Auth::user()->officer) {
        Session::reflash();
        return Redirect::to_route('my_projects');
      } else {
        Session::reflash();
        return Redirect::to_route('projects');
      }
      // When we have something better...
      //$view = View::make('home.index_signed_in');
    } else {
      $view = View::make('home.index_signed_out');
    }
    $this->layout->content = $view;
  }

}