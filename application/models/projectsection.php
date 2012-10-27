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

  public function fork($from_project_id) {
    return new ProjectSection(array('section_category' => $this->category,
                                    'title' => $this->title,
                                    'body' => $this->body,
                                    'based_on_project_section_id' => $this->id,
                                    'created_by_project_id' => $from_project_id));
  }

  public static function change_times_used($section_ids_or_array, $direction) {
    if (!is_array($section_ids_or_array)) $section_ids_or_array = array($section_ids_or_array);

    foreach($section_ids_or_array as $section_id) {
      $section = ProjectSection::find($section_id);
      if ($section) {
        $new_times_used = $section->times_used + $direction;
        if ($new_times_used >= 0) $section->times_used = $new_times_used;
        $section->save();
      }
    }
  }

  // If someone is editing a section they created, we don't want to create
  // a copy of it each time they make a change.
  //
  // However, if someone else is already using that section, or the user editing
  // the section is not the *creator* of that section, we'll have to fork the section
  // first.
  public function can_edit_without_forking() {
    if ($this->times_used > 1) return false;
    if (!$this->created_by_project || !$this->created_by_project->is_mine()) return false;
    return true;
  }
}
