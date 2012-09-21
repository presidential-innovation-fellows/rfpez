<?php

class Officer extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('user_id', 'phone', 'fax', 'name', 'title', 'agency');

  public function validator() {
    $rules = array('phone' => 'required|numeric',
                   'fax' => 'required|numeric',
                   'name' => 'required',
                   'title' => 'required',
                   'agency' => 'required');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $validator;
  }


  public function contracts() {
    return $this->has_many('Contract');
  }

  public function user() {
    return $this->belongs_to('User');
  }

}
