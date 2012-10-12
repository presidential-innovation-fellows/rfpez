<?php namespace Laravel\Auth\Drivers;

class Rfpez extends Eloquent {

  public function officer() {
    if (!$this->user()) return false;
    return $this->user()->officer ?: false;
  }

  public function vendor() {
    if (!$this->user()) return false;
    return $this->user()->vendor ?: false;
  }

}