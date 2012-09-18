<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  public function services() {
    return $this->has_many_and_belongs_to('Service');
  }

  public function bids() {
    return $this->has_many('Bid');
  }

}
