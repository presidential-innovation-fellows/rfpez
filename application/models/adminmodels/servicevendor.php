<?php namespace AdminModels;

class ServiceVendor extends \Eloquent {

  public static $table = "service_vendor";

  public $columns = array(
    'id',
    'service_id',
    'vendor_id',
    'created_at',
    'updated_at'
  );

  public $edit = array(
    'id',
    'service_id',
    'vendor_id',
    'created_at',
    'updated_at'
  );

}