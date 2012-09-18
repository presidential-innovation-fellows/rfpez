<?php

class Bid extends Eloquent {

  public static $timestamps = true;

  public function vendor() {
    return $this->belongs_to('Vendor');
  }

  public function contract() {
    return $this->belongs_to('Contract');
  }

}
