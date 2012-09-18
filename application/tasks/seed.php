<?php

class Seed_Task {

  public function run() {
    Service::create(array('name' => 'Web Design', 'description' => 'This is the description for web design.'));
    Service::create(array('name' => 'Web Development', 'description' => 'This is the description for web development.'));
    Service::create(array('name' => 'Content Management ', 'description' => 'This is the description for content management.'));
  }

}
