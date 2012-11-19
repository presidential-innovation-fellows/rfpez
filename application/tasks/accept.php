<?php

class Accept_Task {

  public function run($args)
  {
    passthru('php application/tests/codecept.phar run acceptance '.(isset($args[0]) ? $args[0] : '').' --steps --debug');
  }

  public function setup() {
    Laravel\Request::set_env('test');
    Laravel\CLI\Command::run(array('sync:minimal'));
  }

}