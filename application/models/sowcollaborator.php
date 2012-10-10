<?php

class SowCollaborator extends Eloquent {

  public static $timestamps = true;

  public static $table = "sow_collaborators";

  public function sow() {
    return $this->belongs_to('Sow');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

}
