<?php

class Contract extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('agency', 'office', 'set_aside', 'classification_code',
                                    'naics_code', 'proposals_due_at', 'posted_at',
                                    'statement_of_work', 'title');

  public function officer() {
    return $this->belongs_to('Officer');
  }

  public function bids() {
    return $this->has_many('Bid');
  }

  public function questions() {
    return $this->has_many('Question');
  }

  public function current_bid_from($vendor) {
    $bid = Bid::where('contract_id', '=', $this->id)
              ->where('vendor_id', '=', $vendor->id)
              ->where('deleted_by_vendor', '!=', true)
              ->first();

    return $bid ? $bid : false;
  }

  public function get_parsed_deliverables() {
    if (preg_match('/deliverables\=\"(.*)\"/', $this->statement_of_work, $matches)) {
      return explode(',', $matches[1]);
    } else {
      return false;
    }
  }

}
