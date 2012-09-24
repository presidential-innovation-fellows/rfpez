<?php

class Bid extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('approach', 'previous_work', 'other_notes');

  public function vendor() {
    return $this->belongs_to('Vendor');
  }

  public function contract() {
    return $this->belongs_to('Contract');
  }

}

Event::listen('eloquent.saving: Bid', function($model) {
  if (is_array($model->prices)) {
    $model->prices = json_encode($model->prices);
  }
});