<?php namespace AdminModels;

class Question extends \Eloquent {

  public $columns = array(
    'id',
    'project_id',
    'vendor_id',
    'question',
    'answer',
    'answered_by',
    'created_at',
    'updated_at',
  );

  public $edit = array(
    'id',
    'project_id',
    'vendor_id',
    'question',
    'answer',
    'answered_by',
    'created_at',
    'updated_at',
  );

}