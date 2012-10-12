<?php

class Home_Controller extends Base_Controller {

  public function action_index() {
    if (Auth::check()) {
      if (Auth::user()->officer) {
        Session::reflash();
        return Redirect::to_route('my_projects');
      } else {
        Session::reflash();
        if (Auth::user()->vendor && Bid::where_vendor_id(Auth::vendor()->id)->where_deleted_by_vendor(0)->count() ) {
          return Redirect::to_route('my_bids');
        } else {
          return Redirect::to_route('projects');
        }
      }
      // When we have something better...
      //$view = View::make('home.index_signed_in');
    } else {
      $view = View::make('home.index_signed_out');
    }
    $this->layout->content = $view;
  }

}