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

}

Event::listen('eloquent.saving: User', function($model) {

  // Hash the password and store it in the encrypted_password column.
  if (isset($model->attributes["password"])) {
    $model->attributes["encrypted_password"] = Hash::make($model->attributes["password"]);
    unset($model->attributes["password"]);
  }

});


