<?php

class Question extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('contract_id', 'question');


  public function validator() {
    $rules = array('contract_id' => 'required',
                   'vendor_id' => 'required',
                   'question' => 'required');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $validator;
  }

  public function contract() {
    return $this->belongs_to('Contract');
  }

  public function vendor() {
    return $this->belongs_to('Vendor');
  }

}
