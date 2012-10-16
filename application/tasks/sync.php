<?php

class Sync_Task {

  public function run()
  {
    Laravel\CLI\Command::run(array('migrate:reset'));
    Laravel\CLI\Command::run(array('migrate'));
    Laravel\CLI\Command::run(array('seed'));
  }

}