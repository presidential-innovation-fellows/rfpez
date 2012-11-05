<?php

class Officers_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'no_auth')->only(array('new', 'create'));
    $this->filter('before', 'officer_only')->only(array('typeahead', 'update'));
  }

  public function action_index() {

  }

  public function action_update($id) {
    // RESTful update from Backbone
    $officer = Officer::find($id);
    $json = Input::json();

    // We can update the officer's role if we are an Admin or a Super Admin.
    // If we're a Super Admin, we can change the roles of other Super Admins.
    // Super Admins can never modify their own role.
    if (Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)) {
      if ( property_exists($json, "role") &&
          ($officer->role != Officer::ROLE_SUPER_ADMIN || Auth::officer()->is_role_or_higher(Officer::ROLE_SUPER_ADMIN)) &&
          ($officer->role != Officer::ROLE_SUPER_ADMIN || Auth::officer()->id != $officer->id))
        $officer->role = $json->role;
    }

    $officer->save();

    return Response::json($officer->to_array());
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