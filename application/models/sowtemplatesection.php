<?php

class SowTemplateSection extends Eloquent {
  public static $timestamps = true;
  public static $table = 'sow_template_sections';

  public function template() {
    return $this->belongs_to('SowTemplate');
  }

  public function in_sow($sow) {
    return $sow->sow_sections()->where('based_on_sow_template_section_id', '=', $this->id)->first() == true;
  }

  public function best_title() {
    return $this->title;
  }
}
