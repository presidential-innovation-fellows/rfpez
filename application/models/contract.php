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

}
