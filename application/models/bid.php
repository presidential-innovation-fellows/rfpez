<?php

class Bid extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('contract_id', 'approach', 'previous_work', 'other_notes', 'prices');

  public function vendor() {
    return $this->belongs_to('Vendor');
  }

  public function contract() {
    return $this->belongs_to('Contract');
  }

  public function prices() {
    if ($this->prices) return json_decode($this->prices, true);
  }

  public function dismiss($reason, $explanation = false) {
    if (!$explanation) $explanation = $reason;
    $this->dismissal_reason = $reason;
    $this->dismissal_explanation = $explanation;
    $this->save();
  }

}

Event::listen('eloquent.saving: Bid', function($model) {
  if (is_array($model->prices)) {
    $model->prices = json_encode($model->prices);
  }
});