<?php

class Comment extends Eloquent {

  public static $timestamps = true;

  public function project() {
    return $this->belongs_to('Project');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

}
