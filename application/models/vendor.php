<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  public static $ballpark_prices = array(1 => "$10,000 - $25,000",
                                         2 => "$25,000 - $50,000",
                                         3 => "$50,000 - $100,000",
                                         4 => "$100,000+");

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
                   'homepage_url' => 'required|url',
                   'portfolio_url' => 'url',
                   'sourcecode_url' => 'url',
                   'image_url' => 'required|url');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $this->validator = $validator;
  }

  public function set_homepage_url($value) {
    $this->attributes['homepage_url'] = (!$value || preg_match('/^https?\:\/\//i', $value)) ? $value : 'http://' . $value;
  }

  public function set_portfolio_url($value) {
    $this->attributes['portfolio_url'] = (!$value || preg_match('/^https?\:\/\//i', $value)) ? $value : 'http://' . $value;
  }

  public function set_image_url($value) {
    $this->attributes['image_url'] = (!$value || preg_match('/^https?\:\/\//i', $value)) ? $value : 'http://' . $value;
  }

  public function set_sourcecode_url($value) {
    $this->attributes['sourcecode_url'] = (!$value || preg_match('/^https?\:\/\//i', $value)) ? $value : 'http://' . $value;
  }

  public function get_ballpark_price_display() {
    return self::$ballpark_prices[$this->ballpark_price];
  }

  public function get_homepage_url_pretty() {
    return preg_replace('/http\:\/\/(www.)?/', '', $this->homepage_url);
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

// If DUNS number is updated, search for related DSBS and SAM records.
Event::listen('eloquent.saving: Vendor', function($model){
  if ($model->changed('duns')) {

    // Get DSBS data
    if ($duns_contents = @file_get_contents("http://rfpez-apis.presidentialinnovationfellows.org/bizs?duns=" . $model->duns)) {
      $duns_json = json_decode($duns_contents, true);
      if (isset($duns_json["results"]) && isset($duns_json["results"][0])) {
        if (trim($duns_json["results"][0]["name"]) != "") $model->dsbs_name = trim($duns_json["results"][0]["name"]);
        if ($duns_json["results"][0]["user_id"]) $model->dsbs_user_id = $duns_json["results"][0]["user_id"];
      }
    }

    // Get SAM.gov data


  }
});
