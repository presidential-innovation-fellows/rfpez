<?php

class Officers_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'no_auth')->only(array('new', 'create'));
    $this->filter('before', 'officer_only')->only(array('typeahead'));
  }

  public function action_new() {
    $view = View::make('officers.new');
    $this->layout->content = $view;
  }

  public function action_typeahead() {
    $results = User::where('users.email', 'LIKE', '%'.Input::get('query').'%')
                   ->where('users.id', '!=', Auth::user()->id)
                   ->where_not_null('users.encrypted_password')
                   ->join('officers', 'users.id', '=', 'officers.user_id')
                   ->lists('email');

    return Response::json($results);
  }

  public function action_create() {
    $user = new User(Input::get('user'));
    $officer = new Officer(Input::get('officer'));

    if ($user->validator(false, true)->passes() && $officer->validator()->passes()) {
      $user->save();
      $user->officer()->insert($officer);
      $user->generate_reset_password_token();
      Mailer::send("FinishOfficerRegistration", array("user" => $user));
      return Redirect::to('/')->with('notice', 'Please check your email for a link to finish signup.');
    } else {
      Session::flash('errors', array_merge($user->validator(false, true)->errors->all(), $officer->validator()->errors->all()));
      return Redirect::to_route('new_officers')->with_input();
    }
  }

}