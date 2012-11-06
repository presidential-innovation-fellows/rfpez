<?php

class Deliverable extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('name', 'date', 'length');

  public function project() {
    return $this->belongs_to('Vendor');
  }

}