<?php

class Deliverable extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('name', 'date', 'length', 'sort_order');

  public function project() {
    return $this->belongs_to('Vendor');
  }

  public function date_or_length() {
    return $this->date ?: $this->length;
  }

}