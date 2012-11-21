<?php namespace AdminModels;

class ProjectSection extends \Eloquent {

  public static $table = "project_sections";

  public $columns = array(
    'id',
    'based_on_project_section_id',
    'times_used',
    'section_category',
    'title',
    'body',
    'created_at',
    'updated_at',
    'created_by_project_id',
    'public'
  );

  public $edit = array(
    'id',
    'based_on_project_section_id',
    'times_used',
    'section_category',
    'title',
    'body',
    'created_at',
    'updated_at',
    'created_by_project_id',
    'public'
  );

}