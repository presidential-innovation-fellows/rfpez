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

    $rules = array('more_info' => 'required',
                   'company_name' => 'required',
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

  public function sync_with_dsbs() {
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
  }

  public function sync_with_sam() {
    if ($sam_contents = @file_get_contents("http://rfpez-apis.presidentialinnovationfellows.org/samzombie/" . $this->duns)) {
      $sam_json = json_decode($sam_contents, true);
      if (isset($sam_json["name"]) && $sam_json["duns"] == $this->duns) {
        if (trim($sam_json["name"]) != "") $this->sam_entity_name = trim($sam_json["name"]);
      } else {
        $this->sam_entity_name = null;
      }
    }
  }

  public function sync_with_epls() {
    if ($epls_contents = @file_get_contents("http://rfpez-apis.presidentialinnovationfellows.org/exclusions?duns=" . $this->duns)) {
      $epls_json = json_decode($epls_contents, true);
      if (isset($epls_json["results"]) && isset($epls_json["results"][0]) && isset($epls_json["results"][0]['exclusion_type'])) {
        $this->epls = true;
      } else {
        $this->epls = false;
      }
    }
  }

  public function geocode() {
    $address = $this->address . " " . $this->city . ", " . $this->state . " " . $this->zip;
    if ($geocode_contents = @file_get_contents("http://50.17.218.115/maps/api/geocode/json?sensor=false&address=".rawurlencode($address))) {
      $geocode_json = json_decode($geocode_contents, true);
      if (isset($geocode_json["results"]) && isset($geocode_json["results"][0]) && isset($geocode_json["results"][0]["geometry"]) && isset($geocode_json["results"][0]["geometry"]["location"])) {
        $this->latitude = $geocode_json["results"][0]["geometry"]["location"]["lat"];
        $this->longitude = $geocode_json["results"][0]["geometry"]["location"]["lng"];
      }
    }
  }

  // SCOPES FOR SERIALIZATION //

  public static function to_array_for_vendor($models) {
    if ($models instanceof Laravel\Database\Eloquent\Model) {
      return self::serialize_for_vendor($models);
    }

    return array_map(function($m) { return self::serialize_for_vendor($m); }, $models);
  }

  public static function serialize_for_vendor($model) {
    $old_hidden = self::$hidden;

    // define new $hidden properties
    self::$hidden = array('dsbs_user_id', 'epls');

    $return_array = $model->to_array();

    self::$hidden = $old_hidden;
    return $return_array;
  }

}

Event::listen('eloquent.saving: Vendor', function($model){
  if ($model->duns && ($model->changed('duns') || ($model->duns && (!$model->sam_entity_name || !$model->dsbs_user_id)))) {
    $model->sync_with_dsbs();
    $model->sync_with_sam();
    $model->sync_with_epls();
  }

  if ($model->changed('address')) {
    $model->geocode();
  }
});
