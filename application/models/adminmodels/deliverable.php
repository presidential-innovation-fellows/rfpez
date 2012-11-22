<?php namespace AdminModels;

class Deliverable extends \Eloquent {

 public $columns = array(
    'id',
    'project_id',
    'date',
    'sort_order',
    'name',
    'created_at',
    'updated_at'
  );

 public $edit = array(
    'id',
    'project_id',
    'date',
    'sort_order',
    'name',
    'created_at',
    'updated_at'
  );

}