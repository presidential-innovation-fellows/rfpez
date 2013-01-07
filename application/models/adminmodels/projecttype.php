<?php namespace AdminModels;

class ProjectType extends \Eloquent {

  public static $table = "project_types";

  public $columns = array(
    'id',
    'name',
    'naics',
    'created_at',
    'updated_at',
    'threshold',
    'show_in_list'
  );

  public $edit = array(
    'id',
    'name',
    'naics',
    'created_at',
    'updated_at',
    'threshold',
    'show_in_list'
  );

}