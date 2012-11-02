<?php

class Comment extends Eloquent {

  public static $timestamps = true;

  public static $hidden = array();

  public $includes = array('project', 'officer');

  public function to_array() {
    $comment = parent::to_array();
    $comment["formatted_created_at"] = $this->formatted_created_at();
    return $comment;
  }

  public function project() {
    return $this->belongs_to('Project');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

  public function is_mine() {
    if (!Auth::officer()) return false;
    return (Auth::officer()->id == $this->officer->id) ? true : false;
  }

  public function formatted_created_at() {
    return date('c', is_object($this->created_at) ? $this->created_at->getTimestamp() : strtotime($this->created_at));
  }

}
