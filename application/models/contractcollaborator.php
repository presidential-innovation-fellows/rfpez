<?php

class ContractCollaborator extends Eloquent {

  public static $timestamps = true;

  public function contract() {
    return $this->belongs_to('Contract');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

}
