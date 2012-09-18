<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  public static $ballpark_prices = array(1 => "$10,000 - $25,000",
                                         2 => "$25,000 - $50,000");

  public function validator($more_attributes = array()) {
    $rules = array('company_name' => 'required',
                   'contact_name' => 'required',
                   'email' => 'required|email|unique:vendors|unique:officers',
                   'password' => 'required|min:6',
                   'address' => 'required',
                   'city' => 'required',
                   'state' => 'required|max:2',
                   'zip' => 'required|numeric',
                   'ballpark_price' => 'required|numeric',
                   'portfolio_url' => 'required|url');

    return Validator::make(array_merge($this->attributes, $more_attributes), $rules);
  }

  public function services() {
    return $this->has_many_and_belongs_to('Service');
  }

  public function bids() {
    return $this->has_many('Bid');
  }

}

Event::listen('eloquent.saving: Vendor', function($model) {

  // Hash the password and store it in the encrypted_password column.
  if (isset($model->attributes["password"])) {
    $model->attributes["encrypted_password"] = Hash::make($model->attributes["password"]);
    unset($model->attributes["password"]);
  }

});


