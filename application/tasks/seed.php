<?php

class Seed_Task {

  public function run() {

    // Create services for vendor profiles
    Service::create(array('name' => 'Web Design', 'description' => 'This is the description for web design.'));
    Service::create(array('name' => 'Web Development', 'description' => 'This is the description for web development.'));
    Service::create(array('name' => 'Content Management ', 'description' => 'This is the description for content management.'));

    // Create project types
    ProjectType::create(array('name' => 'Website Design', 'naics' => 541092));
    ProjectType::create(array('name' => 'API Design and Development', 'naics' => 541093));
    ProjectType::create(array('name' => 'Content Management System Integration', 'naics' => 541094));

    for ($i = 0; $i < 5; $i++) Factory::vendor();
    for ($i = 0; $i < 5; $i++) Factory::officer();
    // for ($i = 0; $i < 10; $i++) Factory::project();
    // for ($i = 0; $i < 15; $i++) Factory::bid();
    // for ($i = 0; $i < 15; $i++) Factory::question();

    // for ($i = 0; $i < 10; $i++) Factory::vendor();

    // $p = Factory::project(array('title' => 'New Website for SBA.gov'));
    // for ($i = 0; $i < 40; $i++) Factory::bid(array(), $p->id);

  }

}
