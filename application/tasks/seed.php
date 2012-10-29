<?php

class Seed_Task {

  public function run() {

    $faker = Faker\Factory::create();

    // Create services for vendor profiles
    Service::create(array('name' => 'Web Design', 'description' => 'This is the description for web design.'));
    Service::create(array('name' => 'Web Development', 'description' => 'This is the description for web development.'));
    Service::create(array('name' => 'Content Management ', 'description' => 'This is the description for content management.'));

    for ($i = 0; $i < 5; $i++) Factory::vendor();
    for ($i = 0; $i < 5; $i++) Factory::officer();

    // Create project types
    ProjectType::create(array('name' => 'Website Design', 'naics' => 541092));
    $api_project_type = ProjectType::create(array('name' => 'API Design and Development', 'naics' => 541093));
    ProjectType::create(array('name' => 'Content Management System Integration', 'naics' => 541094));

    // Create project sections
    $section1 = ProjectSection::create(array('section_category' => 'Deliverables',
                                             'title' => 'Information Architecture',
                                             'public' => true,
                                             'body' => 'Information architecture (IA) and initial site map for the redesigned {{WEBSITE}} site. This IA and site map will be used so copy can be revised and/or developed in-house while the contractor is in the process of executing other deliverable products. Note the IA and site map will only include content/sites currently in the {{WEBSITE}} infrastructure.'));

    $section2 = ProjectSection::create(array('section_category' => 'Deliverables',
                                             'title' => 'Page Templating',
                                             'public' => true,
                                             'body' => 'Complete redesign of {{WEBSITE}} and templates for outreach materials. This includes the complete website, features, applications, blog, social media, mobile, email and newsletters, and any items deemed best to connect with the audiences identified in Deliverable Product #1.'));

    $section3 = ProjectSection::create(array('section_category' => 'Objectives',
                                             'title' => 'Increased Visibility',
                                             'public' => true,
                                             'body' => $faker->paragraph));

    // Link project sections to api project type
    $api_project_type->project_sections()->sync(array($section1->id, $section2->id, $section3->id));

    // Create first project
    $project = Project::create(array('project_type_id' => $api_project_type->id,
                                     'title' => 'API for SBA.gov Dynamic Small Business Search',
                                     'agency' => 'Small Business Administration',
                                     'office' => 'Office of Innovation and Research',
                                     'recommended' => true,
                                     'public' => true,
                                     'background' => $faker->paragraph,
                                     'sections' => array($section1->id, $section2->id, $section3->id),
                                     'variables' => array('WEBSITE' => 'api.dsbs.sba.gov'),
                                     'deliverables' => array('Information Architecture' => '1/1/13', 'Page Templating' => '2/8/13'),
                                     'proposals_due_at' => new \DateTime('12/31/2012'),
                                     'fbo_solnbr' => 'SEED'
                                     ));

    // ...And give it to officer1
    $project->officers()->attach(Officer::first()->id, array('owner' => true));

    for ($i = 0; $i < 5; $i++) Factory::project($project->id);
    for ($i = 0; $i < 15; $i++) Factory::bid();
    for ($i = 0; $i < 15; $i++) Factory::question();
    for ($i = 0; $i < 10; $i++) Factory::vendor();
    for ($i = 0; $i < 10; $i++) Factory::section();

    for ($i = 0; $i < 40; $i++) Factory::bid(array(), $project->id);

  }

}
