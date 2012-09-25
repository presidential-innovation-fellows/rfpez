<?php

class Bid extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('contract_id', 'approach', 'previous_work', 'other_notes', 'prices');

  public function validator() {
    $rules = array('approach' => 'required',
                   'previous_work' => 'required',
                   'prices' => 'required');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $validator;
  }

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

  public function total_price() {
    $total = 0;
    foreach($this->prices() as $deliv => $price) {
      $total += floatVal($price);
    }
    return $total;
  }

}

Event::listen('eloquent.saving: Bid', function($model) {
  if (is_array($model->prices)) {
    $model->prices = json_encode($model->prices);
  }
});