<?php namespace AdminModels;

class Bid extends \Eloquent {

  public $columns = array(
    'id',
    'vendor_id',
    'project_id',
    'approach',
    'previous_work',
    'employee_details',
    'prices',
    'starred',
    'dismissed_at',
    'dismissal_reason',
    'dismissal_explanation',
    'awarded_at',
    'awarded_message',
    'awarded_by',
    'epls_names',
    'deleted_at',
    'submitted_at',
    'created_at',
    'updated_at'
  );

  public $edit = array(
    'id',
    'vendor_id',
    'project_id',
    'approach',
    'previous_work',
    'employee_details',
    'prices',
    'starred',
    'dismissed_at',
    'dismissal_reason',
    'dismissal_explanation',
    'awarded_at',
    'awarded_message',
    'awarded_by',
    'epls_names',
    'deleted_at',
    'submitted_at',
    'created_at',
    'updated_at'
  );

}