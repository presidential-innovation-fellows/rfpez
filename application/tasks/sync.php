<?php

class Sync_Task {

  public function run()
  {
    chdir(path('base'));
    passthru('php artisan migrate:reset --env=local');
    passthru('php artisan migrate --env=local');
    passthru('php artisan seed --env=local');
  }

}