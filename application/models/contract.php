<?php

class Contract extends Eloquent {

  public static $timestamps = true;

  public static $naics_codes = array(541430 => 'Graphic Design Services',
                                     54151 => 'Computer Systems Design and Related Services',
                                     541511 => 'Custom Computer Programming Services',
                                     518210 => 'Data Processing, Hosting, and Related Services',
                                     512110 => 'Video production',
                                     512191 => 'Video post-production services',
                                     518210 => 'Web hosting',
                                     541850 => 'Display advertising services',
                                     541840 => 'Media advertising representatives');

  public static $accessible = array('agency', 'office', 'set_aside', 'classification_code',
                                    'naics_code', 'proposals_due_at', 'posted_at',
                                    'statement_of_work', 'title');

  public static $my_bid_id_list = null;

  public function officer() {
    return $this->belongs_to('Officer');
  }

  public function bids() {
    return $this->has_many('Bid');
  }

  public function questions() {
    return $this->has_many('Question');
  }

  public function is_mine() {
    return Auth::user() && Auth::user()->officer && Auth::user()->officer->user->id == $this->officer->user->id;
  }

  public function my_bid_id() {
    if (!Auth::user() || !Auth::user()->vendor) return false;
    if (self::$my_bid_id_list === null) self::$my_bid_id_list = Auth::user()->vendor->bids()->lists('contract_id');
    if (in_array($this->id, self::$my_bid_id_list)) return $this->id;

    return false;
  }

  public function current_bid_from($vendor) {
    $bid = Bid::where('contract_id', '=', $this->id)
              ->where('vendor_id', '=', $vendor->id)
              ->where('deleted_by_vendor', '!=', true)
              ->first();

    return $bid ? $bid : false;
  }

  public function sow_variable($var) {
    if (preg_match('/'.$var.'\=\"(.*)\"/', $this->statement_of_work, $matches)) {
      return $matches[1];
    } else {
      return false;
    }
  }

  public function get_parsed_deliverables() {
    if ($deliverables = $this->sow_variable('deliverables')) {
      return explode(',', $deliverables);
    } else {
      return false;
    }
  }

  public function get_parsed_deliverables_list() {
    if ($parsed_deliverables = $this->get_parsed_deliverables()) {
      return implode(', ', $parsed_deliverables);
    } else {
      return '';
    }
  }

}
