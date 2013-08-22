<?php

class OutboundExit extends Eloquent {

  public static $timestamps = false;

  public $includes = array('project', 'user');

  public static $accessible = array('user_id', 'page_url', 'outbound_url', 'when');

  // public function user() {
  //   return $this->belongs_to('User');
  // }

  // public function date_or_length() {
  //   return $this->date ?: $this->length;
  // }

}