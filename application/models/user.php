<?php

class User extends Eloquent {

  public static $timestamps = true;

  public $includes = array('vendor', 'officer');

  public function validator($more_attributes = array()) {
    $rules = array('email' => 'required|email|unique:users',
                   'password' => 'required|min:8');

    return Validator::make(array_merge($this->attributes, $more_attributes), $rules);
  }

  public function vendor() {
    return $this->has_one('Vendor');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

  public function is_vendor() {
    return $this->vendor ? true : false;
  }

  public function is_officer() {
    return $this->officer ? true : false;
  }

}

Event::listen('eloquent.saving: User', function($model) {

  // Hash the password and store it in the encrypted_password column.
  if (isset($model->attributes["password"])) {
    $model->attributes["encrypted_password"] = Hash::make($model->attributes["password"]);
    unset($model->attributes["password"]);
  }

});


