<?php

class ProjectSection extends Eloquent {

  public static $timestamps = true;

  public static $table = "project_sections";

  public function based_on_project_section() {
    return $this->belongs_to('ProjectSection', 'based_on_project_section_id');
  }

  public function project_types() {
    return $this->has_many_and_belongs_to('ProjectType', 'project_section_type', 'project_section_id', 'project_type_id');
  }

  public function created_by_project() {
    return $this->belongs_to('Project', 'created_by_project_id');
  }
}
