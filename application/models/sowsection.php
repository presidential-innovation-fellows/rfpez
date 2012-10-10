<?php

class SowSection extends Eloquent {

  public static $table = 'sow_sections';

  public static $timestamps = true;

  public function sow() {
    return $this->belongs_to('Sow');
  }

  public function template_section() {
    return $this->belongs_to('SowTemplateSection', 'based_on_sow_template_section_id');
  }

  public function best_title() {
    return $this->title != "" ? $this->title : $this->template_section->title;
  }

}