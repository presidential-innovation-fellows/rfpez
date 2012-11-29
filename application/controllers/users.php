<?php

class Users_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'has_valid_reset_password_token')->only(array('get_reset_password', 'post_reset_password'));

    $this->filter('before', 'has_valid_new_email_confirm_token')->only(array('confirm_new_email'));

    $this->filter('before', 'no_auth')->only(array('get_forgot_password', 'post_forgot_password',
                                                   'get_reset_password', 'post_reset_password'));

    $this->filter('before', 'auth')->only(array('get_account', 'post_account', 'get_change_email', 'post_change_email',
                                                'get_change_password', 'post_change_password', 'view_notification_payload'));
  }


  public function action_get_forgot_password() {
    $view = View::make('users.get_forgot_password');
    $this->layout->content = $view;
  }

  public function action_post_forgot_password() {
    $user = User::where_email(Input::get('email'))->first();
    if (!$user) {
      Session::flash('errors', array(__("r.flashes.forgot_password_user_not_found")));
      return Redirect::to_route('forgot_password')->with_input();
    }
    $user->generate_reset_password_token();
    Mailer::send("ForgotPassword", array('user' => $user));
    Session::flash('notice', __("r.flashes.forgot_password_success"));
    return Redirect::to_route('signin');
  }

  public function action_get_reset_password() {
    $view = View::make('users.get_reset_password');
    $view->user = Config::get('user');
    $view->finish_signup = $view->user->sign_in_count == 0 ? true : false;
    $this->layout->content = $view;
  }

  public function action_post_reset_password() {
    $user = Config::get('user');

    if (!$user->banned_at && $user->reset_password_to(Input::get('password'))) {
      Session::regenerate();
      Auth::login($user->id);
      $user->track_signin();

      // redirect to account page if this user is an officer who was invited
      // to the site and needs to finish their officer profile.
      if ($user->officer && !$user->officer->name) return Redirect::to('account');

      return Redirect::to('/');
    } else {
      Session::flash('errors', array(__("r.flashes.reset_password_invalid")));
      return Redirect::to_route('reset_password', array($user->reset_password_token));
    }
  }

  public function action_get_account() {
    $view = View::make('users.account');
    $this->layout->content = $view;
  }

  public function action_post_account() {

    if ($vendor = Auth::user()->vendor) {

      $vendor->fill(Input::get('vendor'));
      if ($vendor->validator()->passes()) {
        $services = Input::get('services') ? array_keys(Input::get('services')) : array();
        $vendor->services()->sync($services);
        $vendor->save();
        return Redirect::to_route('account')->with('notice', 'Changes successfully saved.');
      } else {
        Session::flash('errors', $vendor->validator()->errors->all());
        return Redirect::to_route('account')->with_input();
      }

    } else if ($officer = Auth::user()->officer) {
      $officer->fill(Input::get('officer'));
      if ($officer->validator()->passes()) {
        $officer->save();
        return Redirect::to_route('account')->with('notice', 'Changes successfully saved.');
      } else {
        Session::flash('errors', $officer->validator()->errors->all());
        return Redirect::to_route('account')->with_input();
      }

    }
  }

  public function action_get_change_email() {
    $view = View::make('users.change_email');
    $this->layout->content = $view;
  }

  public function action_post_change_email() {
    $user = Auth::user();

    if (!Hash::check(Input::get('password'), $user->encrypted_password)) {
      Session::flash('errors', array('Incorrect password.'));
      return Redirect::to_route('change_email')->with_input();
    }

    if ($user->vendor) {
      $user->email = Input::get('new_email');

      if ($user->validator(false)->passes()) {
        $user->save();
        return Redirect::to_route('account')->with('notice', 'Your email address has been updated.');
      } else {
        Session::flash('errors', $user->validator(false)->errors->all());
        return Redirect::to_route('change_email')->with_input();
      }

    } else {
      // For officers, they will first need to confirm that they
      // own their new email address with a link we email to them.

      $user->new_email = Input::get('new_email');
      $user->new_email_confirm_token = Str::random(36);

      $validator = Validator::make(array('new_email'=>$user->new_email),
                                   array('new_email' => 'required|email|unique:users,email,'.$user->id.'|dotgovonly'));

      if ($validator->passes()) {
        $user->save();
        return Redirect::to_route('account')->with('notice', 'Please check your inbox for a link to confirm your new email address.');
      } else {
        Session::flash('errors', $validator->errors->all());
        return Redirect::to_route('change_email')->with_input();
      }


    }

  }

  public function action_confirm_new_email() {
    $user = Config::get('user');
    $user->confirm_new_email();
    return Redirect::to_route('root')->with('notice', 'Your email address has been successfully updated.');
  }

  public function action_get_change_password() {
    $view = View::make('users.change_password');
    $this->layout->content = $view;
  }

  public function action_post_change_password() {
    $user = Auth::user();

    if (!Hash::check(Input::get('old_password'), $user->encrypted_password)) {
      Session::flash('errors', array('Incorrect password.'));
      return Redirect::to_route('change_password');
    }

    if (Input::get('new_password') !== Input::get('confirm_new_password')) {
      Session::flash('errors', array('Two passwords did not match.'));
      return Redirect::to_route('change_password');
    }

    $user->password = Input::get('new_password');

    if ($user->validator()->passes()) {
      $user->save();
      Session::flash('notice', 'Your password was successfully changed.');
      return Redirect::to_route('account');
    } else {
      Session::flash('errors', $user->validator()->errors->all());
      return Redirect::to_route('change_password');
    }

  }

  public function action_view_notification_payload($key, $val) {
    $user = Auth::user();
    $user->view_notification_payload($key, $val, Input::get('action'));
    return Response::json(array("status" => "success",
                                "unread_count" => $user->unread_notification_count()));
  }


}

Route::filter('has_valid_reset_password_token', function() {
  $token = Request::$route->parameters[0];
  $user = User::where_reset_password_token($token)->first();
  if (!$user) return Redirect::to('/');
  Config::set('user', $user);
});

Route::filter('has_valid_new_email_confirm_token', function() {
  $token = Request::$route->parameters[0];
  $user = User::where_new_email_confirm_token($token)->first();
  if (!$user) return Redirect::to('/');
  Config::set('user', $user);
});
