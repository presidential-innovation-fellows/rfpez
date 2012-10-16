<?php

class Accept_Task {

  public function run()
  {
    if(!Laravel\Request::is_env('test')) {
      die("Umm, you probably don't want to run this with anything other than --env=test\n");
    }

    Laravel\CLI\Command::run(array('sync'));
    passthru('php codecept.phar run acceptance --steps --debug');
  }

}