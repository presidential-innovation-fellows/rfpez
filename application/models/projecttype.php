<?php

class ProjectType extends Eloquent {

  public static $timestamps = true;

  public static $table = "project_types";

  public static $accessible = array('name', 'naics', 'threshold');

  public function projects() {
    return $this->has_many('Project');
  }

  public function project_sections() {
    return $this->has_many_and_belongs_to('ProjectSection', 'project_section_type', 'project_type_id', 'project_section_id');
  }

  public function image() {
    switch ($this->name) {
      case "Website Design":
        return "/img/development.png";

      case "API Design and Development":
        return "/img/hosting.png";

      case "Content Management System Integration":
        return "/img/editing.png";

      default:
        return "/img/development.png";
    }
  }

  public static function defaults() {
    return self::where_show_in_list(true)->get();
  }
}
