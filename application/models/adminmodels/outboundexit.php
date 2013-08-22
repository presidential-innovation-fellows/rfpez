<?php namespace AdminModels;

class OutboundExit extends \Eloquent {

 public $columns = array(
    'id',
    'user_id',
    'page_url',
    'outbound_url',
    'when'
  );

 public $edit = array(
    'id',
    'user_id',
    'page_url',
    'outbound_url',
    'when'
  );

}