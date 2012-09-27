<?php

class Officer extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('user_id', 'phone', 'fax', 'name', 'title', 'agency');

  public $validator = false;

  public function validator() {
    if ($this->validator) return $this->validator;

    $rules = array('phone' => 'required',
                   'fax' => 'required',
                   'name' => 'required',
                   'title' => 'required',
                   'agency' => 'required');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $this->validator = $validator;
  }

  public function contracts() {
    return $this->has_many('Contract');
  }

  public function user() {
    return $this->belongs_to('User');
  }

  public function is_verified() {
    return $this->verified_solnbr ? true : false;
  }

  public function verify_with_solnbr($solnbr) {
    $this->verified_solnbr = $solnbr;
    $this->verified_at = new \DateTime;
    $this->save();
  }

}
