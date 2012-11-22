<?php namespace AdminModels;

class Officer extends \Eloquent {

  public $columns = array(
    'id',
    'user_id',
    'phone',
    'fax',
    'name',
    'title',
    'agency',
    'created_at',
    'updated_at',
    'role'
  );

  public $edit = array(
    'id',
    'user_id',
    'phone',
    'fax',
    'name',
    'title',
    'agency',
    'created_at',
    'updated_at',
    'role'
  );

}