<?php

class Bid extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('contract_id', 'approach', 'previous_work', 'employee_details', 'prices');

  public static $dismissal_reasons = array('Price too high');

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
    return json_decode($this->attributes['prices'], true);
  }

  public function set_prices($value) {
    $this->attributes['prices'] = json_encode($value);
  }

  public function get_deliverable_names() {
    return $this->get_prices() ? array_keys($this->get_prices()) : false;
  }

  public function get_deliverable_prices() {
    return $this->get_prices() ? array_values($this->get_prices()) : false;
  }

  public function dismiss($reason, $explanation = false) {
    if (!$explanation) $explanation = $reason;
    $this->dismissal_reason = $reason;
    $this->dismissal_explanation = $explanation;
    $this->save();
  }

  public function undismiss() {
    $this->dismissal_reason = NULL;
    $this->dismissal_explanation = NULL;
    $this->save();
  }

  public function dismissed() {
    return $this->dismissal_reason ? true : false;
  }

  public function submit() {
    $this->submitted_at = new \DateTime;
    $this->save();
  }

  public function total_price() {
    $total = 0;
    foreach($this->prices as $deliv => $price) {
      $total += floatVal($price);
    }
    return $total;
  }

}