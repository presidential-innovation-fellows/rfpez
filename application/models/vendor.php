<?php

class Vendor extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('company_name', 'contact_name', 'address', 'city', 'state', 'zip',
                                    'latitude', 'longitude', 'ballpark_price', 'more_info', 'homepage_url',
                                    'image_url', 'portfolio_url', 'sourcecode_url', 'duns');

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

  public function ballpark_price_display() {
    return self::$ballpark_prices[$this->ballpark_price];
  }

  public function user() {
    return $this->belongs_to('User');
  }

  public function services() {
    return $this->has_many_and_belongs_to('Service');
  }

  public function bids() {
    return $this->has_many('Bid')->where_null('deleted_at');
  }

  public function ban() {
    $this->user->banned_at = new \DateTime;
    $this->user->save();

    foreach ($this->bids as $bid) {
      if (!$bid->awarded_at) $bid->delete();
    }
  }

  public function sync_with_dsbs_and_sam_and_epls() {

    if (!$this->duns) return;

    // Get DSBS data
    if ($duns_contents = @file_get_contents("http://rfpez-apis.presidentialinnovationfellows.org/bizs?duns=" . $this->duns)) {
      $duns_json = json_decode($duns_contents, true);
      if (isset($duns_json["results"]) && isset($duns_json["results"][0])) {
        if (trim($duns_json["results"][0]["name"]) != "") $this->dsbs_name = trim($duns_json["results"][0]["name"]);
        if ($duns_json["results"][0]["user_id"]) $this->dsbs_user_id = $duns_json["results"][0]["user_id"];
      } else {
        $this->dsbs_name = null;
        $this->dsbs_user_id = null;
      }
    }

    // Get SAM.gov data
    if ($sam_contents = @file_get_contents("http://rfpez-apis.presidentialinnovationfellows.org/samzombie/" . $this->duns)) {
      $sam_json = json_decode($sam_contents, true);
      if (isset($sam_json["name"]) && $sam_json["duns"] == $this->duns) {
        if (trim($sam_json["name"]) != "") $this->sam_entity_name = trim($sam_json["name"]);
      } else {
        $this->sam_entity_name = null;
      }
    }

    // Get EPLS data
    if ($epls_contents = @file_get_contents("http://rfpez-apis.presidentialinnovationfellows.org/exclusions?duns=" . $this->duns)) {
      $epls_json = json_decode($epls_contents, true);
      if (isset($epls_json["results"]) && isset($epls_json["results"][0]) && isset($epls_json["results"][0]['exclusion_type'])) {
        $this->epls = true;
      } else {
        $this->epls = false;
      }
    }
  }

}

// If DUNS number is updated, search for related DSBS and SAM and EPLS records.
Event::listen('eloquent.saving: Vendor', function($model){
  if ($model->changed('duns') || ($model->duns && (!$model->sam_entity_name || !$model->dsbs_user_id)))
    $model->sync_with_dsbs_and_sam_and_epls();
});
