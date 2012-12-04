<?php

class Bid extends SoftDeleteModel {

  public static $timestamps = true;

  public $includes = array('project', 'vendor', 'vendor.user');

  public static $accessible = array('project_id', 'approach', 'previous_work', 'employee_details', 'epls_names', 'prices');

  public static $default_dismissal_reasons = array('Price Too high',
                                           'Not Qualified',
                                           'Irrelevant Proposal',
                                           'Excluded Party',
                                           'Low Value',
                                           'Poor Work History');

  public static $dismissal_reasons = false;

  public $total_price = false;

  public $average_price = false;

  public $cached_prices = false;

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

  public function project() {
    return $this->belongs_to('Project');
  }

  public function is_mine() {
    return (Auth::vendor() && ($this->vendor->id == Auth::vendor()->id)) ? true : false;
  }

  public function get_prices() {
    if ($this->cached_prices !== false) return $this->cached_prices;
    return $this->cached_prices = isset($this->attributes['prices']) ? json_decode($this->attributes['prices'], true) : array();
  }

  public function set_prices($value) {
    $this->attributes['prices'] = json_encode($value);
  }

  public function deliverable_names() {
    return $this->get_prices() ? array_keys($this->get_prices()) : false;
  }

  public function deliverable_prices() {
    return $this->get_prices() ? array_values($this->get_prices()) : false;
  }

  public function get_epls_names() {
    return isset($this->attributes['epls_names']) ? json_decode($this->attributes['epls_names'], true) : array();
  }

  public function set_epls_names($value) {
    $this->attributes['epls_names'] = json_encode($value);
  }

  public function dismiss($reason = false, $explanation = false) {
    $this->dismissed_at = new \DateTime;
    $this->dismissal_reason = $reason;
    $this->dismissal_explanation = $explanation;
    $this->save();

    Notification::send('Dismissal', array('bid' => $this, 'actor_id' => Auth::user() ? Auth::user()->id : null));
  }

  public function undismiss() {
    $this->dismissed_at = NULL;
    $this->dismissal_reason = NULL;
    $this->dismissal_explanation = NULL;
    $this->save();
    Notification::send('Undismissal', array('bid' => $this, 'actor_id' => Auth::user() ? Auth::user()->id : null));
  }

  public function dismissed() {
    return $this->dismissed_at ? true : false;
  }

  public function get_status() {
    if (!$this->submitted_at) {
      return "Draft Saved";
    } elseif ($this->dismissed()) {
      return "Dismissed";
    } elseif ($this->awarded_at) {
      return "Won!";
    } else {
      return "Pending Review";
    }
  }

  public function submit() {
    $this->submitted_at = new \DateTime;
    $this->save();

    foreach ($this->project->officers as $officer) {
      Notification::send('BidSubmit', array('bid' => $this, 'target_id' => $officer->user_id));
    }
  }

  public function award($message) {
    $this->awarded_at = new \DateTime;
    $this->awarded_message = $message;
    $this->awarded_by = Auth::officer()->id;
    $this->save();

    Notification::send("Award", array('actor_id' => Auth::user()->id, 'bid' => $this));

    // Dismiss all the other bids.
    foreach ($this->project->bids as $bid) {
      if ($bid->id != $this->id && !$bid->dismissed_at)
        $bid->dismiss();
    }

    if (trim($message) != "") {
      Mailer::send("BidAwarded", array('bid' => $this));
    }
  }

  public function delete_by_vendor() {
    $this->delete();

    Notification::where_payload_type("bid")->where_payload_id($this->id)->delete();
  }

  public function display_price() {
    if ($this->project->price_type == Project::PRICE_TYPE_FIXED) {
      return $this->total_price();
    } else { // hourly
      return $this->average_price();
    }
  }

  public function total_price() {
    if ($this->total_price !== false) return $this->total_price;
    $total = 0;
    if ($this->prices) {
      foreach($this->prices as $deliv => $price) {
        $total += floatVal($price);
      }
    }
    return $this->total_price = "$" . intval($total);
  }

  public function average_price() {
    if ($this->average_price !== false) return $this->average_price;
    $counter = 0;
    $sum = 0;
    if ($this->prices) {
      foreach($this->prices as $deliv => $price) {
        $counter++;
        $sum += floatVal($price);
      }
    }
    return $this->average_price = "$" . intval($sum / $counter) . "/hr" . ($counter > 1 ? ' (avg)' : '');
  }

  public static function dismissal_reasons() {
    if (self::$dismissal_reasons !== false) return self::$dismissal_reasons;

    $raw_popular_reasons = DB::query("SELECT count(dismissal_reason) AS count, dismissal_reason
                                  FROM bids
                                  GROUP BY dismissal_reason
                                  HAVING count(dismissal_reason) > 2");

    $reasons = array_map (function($raw_popular_reason){
      return $raw_popular_reason->dismissal_reason;
    }, $raw_popular_reasons);

    $returnArray = array_merge(self::$default_dismissal_reasons, $reasons);
    sort($returnArray);
    return self::$dismissal_reasons = $returnArray;
  }

  public function sync_with_epls() {
    if (!$this->employee_details) return;

    $names = preg_split('/\s?(<br\s?\/?>|\\n)\s?/i', $this->attributes['employee_details'], -1, PREG_SPLIT_NO_EMPTY);

    $temp_names = array();
    foreach ($names as $name) {
      $name = trim($name);
      // Check EPLS
      if ($epls_contents = @file_get_contents("http://rfpez-apis.presidentialinnovationfellows.org/exclusions?name=" . urlencode($name))) {
        $epls_json = json_decode($epls_contents, true);
        if (isset($epls_json["results"]) && isset($epls_json["results"][0]) && isset($epls_json["results"][0]['exclusion_type'])) {
          $temp_names[$name] = true;
        } else {
          $temp_names[$name] = false;
        }
      }
    }
    $this->epls_names = $temp_names;
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
    self::$hidden = array('starred', 'dismissal_reason', 'dismissal_explanation', 'updated_at', 'epls_names',
                          'project', 'vendor');

    $return_array = $model->to_array();
    unset($return_array["vendor"]);

    $return_array["project"] = Project::to_array_for_vendor($model->project);

    self::$hidden = $old_hidden;
    return $return_array;
  }

}

