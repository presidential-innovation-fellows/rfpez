<?php

class Officer extends Eloquent {

  public static $timestamps = true;

  public function contracts() {
    return $this->has_many('Contract');
  }

  public function user() {
    return $this->belongs_to('User');
  }

}
