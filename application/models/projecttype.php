<?php

class ProjectType extends Eloquent {

  public static $timestamps = true;

  public static $table = "project_types";

  public function projects() {
    return $this->has_many('Project');
  }

  public function project_sections() {
    return $this->has_many_and_belongs_to('ProjectSection', 'project_section_type');
  }
}
