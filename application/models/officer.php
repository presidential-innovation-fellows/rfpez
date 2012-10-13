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

  public function projects() {
    return $this->has_many_and_belongs_to('Project', 'project_collaborators');
  }

  public function comments() {
    return $this->has_many('Comment');
  }

  public function user() {
    return $this->belongs_to('User');
  }

  public function collaborates_on($project_id) {
    return in_array($project_id, $this->projects()->lists('id'));
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
