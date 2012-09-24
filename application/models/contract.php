<?php

class Contract extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('agency', 'office', 'set_aside', 'classification_code',
                                    'naics_code', 'proposals_due_at', 'posted_at',
                                    'statement_of_work');

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
              ->where(function($query) {
                $query->where('dismissal_reason', '!=', 'Deleted by vendor');
                $query->or_where_null('dismissal_reason');
              })
              ->first();

    return $bid ? $bid : false;
  }

}
