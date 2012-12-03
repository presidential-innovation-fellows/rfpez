<?php

class Question extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('project_id', 'question');

  public $includes_in_array = array('answered_by_officer_email');


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

  public function answerer() {
    return $this->belongs_to('Officer', 'answered_by');
  }

  public function answered_by_officer_email() {
    if ($this->answerer) {
      return $this->answerer->user->email;
    }
  }

  // SCOPES FOR SERIALIZATION //

  public static function to_array_for_vendor($models) {
    if ($models instanceof Laravel\Database\Eloquent\Model) {
      return self::serialize_for_vendor($models);
    }

    return array_map(function($m) { return self::serialize_for_vendor($m); }, $models);
  }

  public static function serialize_for_vendor($model) {
    $old_hidden = self::$hidden;

    // define new $hidden properties
    self::$hidden = array('vendor_id', 'answered_by');

    $return_array = $model->to_array();
    self::$hidden = $old_hidden;
    return $return_array;
  }

}
