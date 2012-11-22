<?php namespace AdminModels;

class Notification extends \Eloquent {

  public $columns = array(
    'id',
    'target_id',
    'actor_id',
    'notification_type',
    'payload',
    'read',
    'created_at',
    'updated_at',
    'payload_id',
    'payload_type',
  );

  public $edit = array(
    'id',
    'target_id',
    'actor_id',
    'notification_type',
    'payload',
    'read',
    'created_at',
    'updated_at',
    'payload_id',
    'payload_type',
  );

}