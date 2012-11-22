<?php namespace AdminModels;

class Comment extends \Eloquent {

  public $columns = array(
    'id',
    'project_id',
    'officer_id',
    'body',
    'created_at',
    'updated_at',
    'deleted_at'
  );

  public $edit = array(
    'id',
    'project_id',
    'officer_id',
    'body',
    'created_at',
    'updated_at',
    'deleted_at'
  );

}