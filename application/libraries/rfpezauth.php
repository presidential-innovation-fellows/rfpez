<?php

class RfpezAuth extends Laravel\Auth\Drivers\Eloquent {

  public function officer() {
    if (!$this->user()) return false;
    return $this->user()->officer ?: false;
  }

  public function vendor() {
    if (!$this->user()) return false;
    return $this->user()->vendor ?: false;
  }

}