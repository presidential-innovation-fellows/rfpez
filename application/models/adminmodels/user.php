<?php namespace AdminModels;

class User extends \Eloquent {

  public $columns = array(
    'id',
    'email',
    'encrypted_password',
    'reset_password_token',
    'reset_password_sent_at',
    'sign_in_count',
    'current_sign_in_at',
    'last_sign_in_at',
    'current_sign_in_ip',
    'last_sign_in_ip',
    'new_email',
    'new_email_confirm_token',
    'send_emails',
    'invited_by',
    'banned_at',
    'how_hear',
    'created_at',
    'updated_at'
  );

  public $edit = array(
    'id',
    'email',
    'encrypted_password',
    'reset_password_token',
    'reset_password_sent_at',
    'sign_in_count',
    'current_sign_in_at',
    'last_sign_in_at',
    'current_sign_in_ip',
    'last_sign_in_ip',
    'new_email',
    'new_email_confirm_token',
    'send_emails',
    'invited_by',
    'banned_at',
    'how_hear',
    'created_at',
    'updated_at'
  );

}