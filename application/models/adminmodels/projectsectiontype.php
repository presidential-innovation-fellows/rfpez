<?php namespace AdminModels;

class ProjectSectionType extends \Eloquent {

  public static $table = "project_section_type";

  public $columns = array(
    'id',
    'project_type_id',
    'project_section_id',
    'created_at',
    'updated_at'
  );

  public $edit = array(
    'id',
    'project_type_id',
    'project_section_id',
    'created_at',
    'updated_at'
  );

}