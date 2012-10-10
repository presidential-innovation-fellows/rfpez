<?php

class SowTemplate extends Eloquent {
  public static $timestamps = true;
  public static $table = "sow_templates";

  public function template_sections() {
    return $this->has_many('SowTemplateSection', 'sow_template_id');
  }

  public function get_variable($key) {
    return isset($this->variables[$key]) ? $this->variables[$key] : "";
  }

  public function get_variables() {
    return json_decode($this->get_attribute('variables'), true);
  }

  public function set_variables($vars) {
    if (is_array($vars)) {
      $vars = json_encode($vars);
    }
    $this->set_attribute('variables', $vars);
  }

  public function add_variable($key, $val) {
    $variables_array = $this->variables;
    $variables_array[$key] = $val;
    $this->set_variables($variables_array);
  }

}
