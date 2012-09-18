<?php

class Officer extends Eloquent {

  public static $timestamps = true;

  public function contracts() {
    return $this->has_many('Contract');
  }

}
