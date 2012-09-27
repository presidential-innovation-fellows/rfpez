<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  public static $ballpark_prices = array(1 => "$10,000 - $25,000",
                                         2 => "$25,000 - $50,000");

  public $validator = false;

  public function validator() {
    if ($this->validator) return $this->validator;

    $rules = array('company_name' => 'required',
                   'contact_name' => 'required',
                   'address' => 'required',
                   'city' => 'required',
                   'state' => 'required|max:2',
                   'zip' => 'required|numeric',
                   'ballpark_price' => 'required|numeric',
                   'portfolio_url' => 'required|url');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $this->validator = $validator;
  }

  public function user() {
    return $this->belongs_to('User');
  }

  public function services() {
    return $this->has_many_and_belongs_to('Service');
  }

  public function bids() {
    return $this->has_many('Bid');
  }

}
