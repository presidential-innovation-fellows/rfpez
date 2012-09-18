<?php

class Service extends Eloquent {

  public static $timestamps = true;

  public function vendor() {
    return $this->has_many_and_belongs_to('Vendor');
  }

}
