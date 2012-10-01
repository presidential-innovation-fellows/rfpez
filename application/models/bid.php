<?php

class Bid extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('contract_id', 'approach', 'previous_work', 'employee_details', 'prices');

  public function validator() {
    $rules = array('approach' => 'required',
                   'previous_work' => 'required',
                   'employee_details' => 'required',
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

  public function get_prices() {
    return json_decode($this->attributes['prices']);
  }

  public function set_prices($value) {
    $this->attributes['prices'] = json_encode($value);
  }

  public function dismiss($reason, $explanation = false) {
    if (!$explanation) $explanation = $reason;
    $this->dismissal_reason = $reason;
    $this->dismissal_explanation = $explanation;
    $this->save();
  }

  public function dismissed() {
    return $this->dismissal_reason ? true : false;
  }

  public function total_price() {
    $total = 0;
    foreach($this->prices as $deliv => $price) {
      $total += floatVal($price);
    }
    return $total;
  }

}