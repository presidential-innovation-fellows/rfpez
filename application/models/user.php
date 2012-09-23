<?php

class User extends Eloquent {

  public static $timestamps = true;

  public $includes = array('vendor', 'officer');

  public function validator($password_required = true) {
    $rules = array('email' => 'required|email|unique:users');
    if ($password_required) $rules["password"] = "required|min:8";

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $validator;
  }

  public function vendor() {
    return $this->has_one('Vendor');
  }

  public function officer() {
    return $this->has_one('Officer');
  }

  public function is_vendor() {
    return $this->vendor ? true : false;
  }

  public function is_officer() {
    return $this->officer ? true : false;
  }

  public function track_signin() {
    $this->sign_in_count++;
    $this->current_sign_in_ip = Request::ip();
    $this->current_sign_in_at = new \DateTime;
    if (!$this->last_sign_in_ip) $this->last_sign_in_ip = $this->current_sign_in_ip;
    if (!$this->last_sign_in_at) $this->last_sign_in_at = $this->current_sign_in_at;
    $this->save();
  }

  public function generate_reset_password_token() {
    $this->reset_password_token = Str::random(36);
    $this->reset_password_sent_at = new \DateTime;
    $this->save();
  }

  public function reset_password_to($new_password) {
    $validator = Validator::make(array('password' => $new_password), array('password' => 'required|min:8'));

    if ($validator->passes()) {
      $this->password = $new_password;
      $this->reset_password_token = null;
      $this->reset_password_sent_at = null;
      $this->save();
      return true;
    } else {
      return false;
    }
  }

}

Event::listen('eloquent.saving: User', function($model) {
  // Hash the password and store it in the encrypted_password column.
  if (isset($model->attributes["password"])) {
    $model->attributes["encrypted_password"] = Hash::make($model->attributes["password"]);
    unset($model->attributes["password"]);
  }
});


