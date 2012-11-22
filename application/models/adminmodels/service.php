<?php namespace AdminModels;

class Service extends \Eloquent {

  public $columns = array(
    'id',
    'name',
    'description',
    'created_at',
    'updated_at'
  );

  public $edit = array(
    'id',
    'name',
    'description',
    'created_at',
    'updated_at'
  );

}