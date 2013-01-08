<?php

class Seed_Task {

  public function run() {

    // Create the base data.
    $this->base_data();
    $this->minimal_data();
    // $project = Project::first();

    // // Create a bunch more stuff, just for testin'.
    // for ($i = 0; $i < 5; $i++) Factory::project($project->id);
    // for ($i = 0; $i < 15; $i++) Factory::bid();
    // for ($i = 0; $i < 15; $i++) Factory::question();
    // for ($i = 0; $i < 10; $i++) Factory::vendor();
    // for ($i = 0; $i < 40; $i++) Factory::section();

    // for ($i = 0; $i < 20; $i++) Factory::project($project->id);
    // for ($i = 0; $i < 40; $i++) Factory::bid(array(), $project->id);

  }

  public function production() {
    return $this->base_data();
  }

  public function minimal() {
    $this->base_data();
    $this->minimal_data();
  }

  private function base_data() {
    // If the "Web Design" service already exists, assume this task has already been run and exit.
    if (Service::where_name('Web Design')->first()) return;

    // Create services for vendor profiles
    Service::create(array('name' => 'Web Design', 'description' => 'Your focus is on design. You spend your time in graphic design tools.'));
    Service::create(array('name' => 'Web Development', 'description' => 'You write code. PHP, Ruby on Rails, Python, ColdFusion. You write software.'));
    Service::create(array('name' => 'Content Management ', 'description' => 'Your focus is on Content Management. Drupal Integrations, etc.'));
    Service::create(array('name' => 'Social Media Marketing', 'description' => 'Facebook, Twitter, Google+, you help people use Social Media to the best of their ability.'));
    Service::create(array('name' => 'Search Engine Optimization', 'description' => 'You make content discoverable in search engines.'));
    Service::create(array('name' => 'Mobile Application Development', 'description' => 'You make applications for mobile phones.'));
    Service::create(array('name' => 'Video Production', 'description' => 'Your make great online videos'));
    Service::create(array('name' => 'Video Transcription', 'description' => 'You write transcripts of videos.'));

    // Create project types
    $project_types = array();

    $project_types[] = ProjectType::create(array('name' => 'Web Design', 'naics' => 541430, 'threshold' => 7));
    $project_types[] = ProjectType::create(array('name' => 'Web Development', 'naics' => 541511, 'threshold' => 25.5));
    $project_types[] = ProjectType::create(array('name' => 'Content Management', 'naics' => 541511, 'threshold' => 25.5));
    $project_types[] = ProjectType::create(array('name' => 'Social Media Marketing','naics' => 541511, 'threshold' => 25.5));
    $project_types[] = ProjectType::create(array('name' => 'Search Engine Optimization', 'naics' => 541511, 'threshold' => 25.5));
    $project_types[] = ProjectType::create(array('name' => 'Mobile Application Development', 'naics' => 541511, 'threshold' => 25.5));
    $project_types[] = ProjectType::create(array('name' => 'Video Production', 'naics' => 512110, 'threshold' => 29.5));
    $project_types[] = ProjectType::create(array('name' => 'Video Transcription', 'naics' => 561410, 'threshold' => 7));

    foreach ($project_types as $project_type) {
      $project_type->show_in_list = true;
      $project_type->save();
    }
  }

  private function minimal_data() {
    $faker = Faker\Factory::create();
    for ($i = 0; $i < 5; $i++) Factory::vendor();
    for ($i = 0; $i < 5; $i++) Factory::officer();
  }

}
