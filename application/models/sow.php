<?php

class Sow extends Eloquent {
  public static $timestamps = true;

  public function sow_sections() {
    return $this->has_many('SowSection');
  }

  public function template() {
    return $this->belongs_to('SowTemplate', 'based_on_sow_template_id');
  }

  public function get_variables() {
    return json_decode($this->get_attribute('variables'), true);
  }

  public function set_variables($vars) {
    if (is_array($vars)) {
      $vars = json_encode($vars);
    }
    $this->set_attribute('variables', $vars) ;
  }

  public function get_variable($key) {
    return isset($this->variables[$key]) ? $this->variables[$key] : "";
  }

  public function add_variable($key, $val) {
    $variables_array = $this->get_variables();
    $variables_array[$key] = $val;
    $this->set_variables($variables_array);
  }

  public function add_duedate($key, $val) {
    $variables_array = $this->get_variables();
    if (!isset($variables_array["Deliverable Due Dates"])) $variables_array["Deliverable Due Dates"] = array();
    $variables_array["Deliverable Due Dates"][$key] = $val;
    $this->set_variables($variables_array);
  }


  public function background_and_scope() {
    $section = SowSection::where('sow_id', '=', $this->id)
                         ->where('section_type', '=', 'Background & Scope')
                         ->first();

    if ($section) {
      return $section->body;
    }
  }

  public function sections($section_type) {

    if ($section_type == "Deliverables") {

      $deliverables = $this->sow_sections()->where('section_type', '=', 'Deliverables')->get();
      $variables = $this->variables;

      uasort($deliverables, function($a, $b) use ($variables) {
        if (!isset($variables["Deliverable Due Dates"][$a->best_title()])) return false;
        if (!isset($variables["Deliverable Due Dates"][$b->best_title()])) return true;

        return strtotime($variables["Deliverable Due Dates"][$a->best_title()]) >
               strtotime($variables["Deliverable Due Dates"][$b->best_title()]);
      });

      return $deliverables;

    } else {
      return $this->sow_sections()->where('section_type', '=', $section_type)->get();
    }

  }

  public function sow_section_types() {
    $types = array();
    $query = DB::query("SELECT section_type
                        FROM sow_sections
                        WHERE section_type != 'Background & Scope'
                        AND sow_id = ?
                        GROUP BY section_type", array($this->id));

    foreach($query as $type) {
      $types[] = $type->section_type;
    }

    return $types;
  }

  public function template_section_types() {
    $types = array();
    $query = DB::query("SELECT section_type
                        FROM sow_template_sections
                        WHERE sow_template_id = ?
                        GROUP BY section_type
                        ORDER BY display_order", array($this->based_on_sow_template_id));

    foreach($query as $type) {
      $types[] = $type->section_type;
    }

    return $types;
  }

  public function first_template_section_type() {
    $types = $this->template_section_types();
    return $types[0];
  }

  public function last_template_section_type() {
    $types = $this->template_section_types();
    return $types[count($types) - 1];
  }

  public function template_section_type_after($section_type) {
    return $this->template_section_type_sequential(1, $section_type);
  }

  public function template_section_type_before($section_type) {
    return $this->template_section_type_sequential(-1, $section_type);
  }

  public function template_section_type_sequential($direction, $section_type) {
    $types = $this->template_section_types();
    $index = array_search($section_type, $types);
    if (isset($types[$index + $direction])){
      return $types[$index + $direction];
    } else {
      return false;
    }
  }

  public function due_date($deliverable) {
    $variables_array = $this->variables;

    if (isset($variables_array["Deliverable Due Dates"]) &&
        isset($variables_array["Deliverable Due Dates"][$deliverable->best_title()])) {
      return $variables_array["Deliverable Due Dates"][$deliverable->best_title()];
    } else {
      return date('n/j/y');
    }
  }


}
