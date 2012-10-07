<?php

class Seed_Task {

  public function run() {

    Service::create(array('name' => 'Web Design', 'description' => 'This is the description for web design.'));
    Service::create(array('name' => 'Web Development', 'description' => 'This is the description for web development.'));
    Service::create(array('name' => 'Content Management ', 'description' => 'This is the description for content management.'));

    for ($i = 0; $i < 10; $i++) Factory::vendor();
    for ($i = 0; $i < 10; $i++) Factory::officer();
    for ($i = 0; $i < 5; $i++) Factory::contract();
    for ($i = 0; $i < 10; $i++) Factory::bid();
    for ($i = 0; $i < 10; $i++) Factory::question();

  }

}
