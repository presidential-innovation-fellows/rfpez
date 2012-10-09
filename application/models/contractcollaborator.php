<?php

class ContractCollaborator extends Eloquent {

  public static $timestamps = true;

  public static $table = "contract_collaborators";

  public function contract() {
    return $this->belongs_to('Contract');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

}
