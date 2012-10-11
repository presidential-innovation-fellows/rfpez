<?php

class Question extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('project_id', 'question');


  public function validator() {
    $rules = array('project_id' => 'required',
                   'vendor_id' => 'required',
                   'question' => 'required');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $validator;
  }

  public function project() {
    return $this->belongs_to('Project');
  }

  public function vendor() {
    return $this->belongs_to('Vendor');
  }

}
