<?php

class Accept_Task {

  public function run()
  {
    Laravel\Request::set_env('test');

    Laravel\CLI\Command::run(array('sync'));
    passthru('php application/tests/codecept.phar run acceptance --steps --debug');
  }

}