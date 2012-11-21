<?php namespace AdminModels;

class ProjectType extends \Eloquent {

  public static $table = "project_types";

  public $columns = array(
    'id',
    'name',
    'naics',
    'created_at',
    'updated_at',
    'show_in_list'
  );

  public $edit = array(
    'id',
    'name',
    'naics',
    'created_at',
    'updated_at',
    'show_in_list'
  );

}