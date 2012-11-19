<?php

class Sync_Task {

  public function run()
  {
    Laravel\CLI\Command::run(array('migrate:reset'));
    Laravel\CLI\Command::run(array('migrate'));
    Laravel\CLI\Command::run(array('seed'));
  }

  public function production() {
    Laravel\CLI\Command::run(array('migrate:reset'));
    Laravel\CLI\Command::run(array('migrate'));
    Laravel\CLI\Command::run(array('seed:production'));
  }

  public function minimal() {
    Laravel\CLI\Command::run(array('migrate:reset'));
    Laravel\CLI\Command::run(array('migrate'));
    Laravel\CLI\Command::run(array('seed:minimal'));
  }

}