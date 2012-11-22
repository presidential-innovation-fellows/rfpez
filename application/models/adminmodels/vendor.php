<?php namespace AdminModels;

class Vendor extends \Eloquent {

  public $columns = array(
    'id',
    'user_id',
    'company_name',
    'contact_name',
    'address',
    'city',
    'state',
    'zip',
    'latitude',
    'longitude',
    'ballpark_price',
    'more_info',
    'homepage_url',
    'image_url',
    'portfolio_url',
    'sourcecode_url',
    'duns',
    'sam_entity_name',
    'dsbs_name',
    'dsbs_user_id',
    'epls',
    'created_at',
    'updated_at'
  );

  public $edit = array(
    'id',
    'user_id',
    'company_name',
    'contact_name',
    'address',
    'city',
    'state',
    'zip',
    'latitude',
    'longitude',
    'ballpark_price',
    'more_info',
    'homepage_url',
    'image_url',
    'portfolio_url',
    'sourcecode_url',
    'duns',
    'sam_entity_name',
    'dsbs_name',
    'dsbs_user_id',
    'epls',
    'created_at',
    'updated_at'
  );

}