<?php

class Question extends Eloquent {

  public static $timestamps = true;

  public function contract() {
    return $this->belongs_to('Contract');
  }

  public function vendor() {
    return $this->belongs_to('Vendor');
  }

}
