<?php

class ProjectCollaborator extends Eloquent {

  public static $timestamps = true;

  public static $table = "project_collaborators";

  public function project() {
    return $this->belongs_to('Project');
  }

  public function officer() {
    return $this->belongs_to('Officer');
  }

}
