<?php

Class Factory {

  public static $vendor_count = 1;

  public static $officer_count = 1;

  public static $agencies = array("Department of Justice", "Health and Human Services", "Small Business Administration",
                                  "General Services Administration", "Department of Education");

  public static $offices = array("Office of Capital Access", "Office of Credit Risk Management", "Office of Investment",
                                 "Office of Field Operations", "Office of Surety Guarantees", "Office of Hearings & Appeals");

  public static $project_titles = array("API for Energy.gov", "Census API", "MyGov's Sweet API", "Bluth Banana Stand API");

  public static $solnbrs = array("VA24412Q1868", "VA256-12-Q-2266", "SPM7L413V0156", "N00183-12-T-0557-0001",
                                 "W911N2-12-R-0081", "SPRPA112QX020", "N0038307G002J7371");

  public static function vendor() {
    $faker = Faker\Factory::create();

    $image_urls = array('http://i.imgur.com/Gh4ZX.png', 'http://i.imgur.com/vySFV.png', 'http://i.imgur.com/RdBae.png',
                        'http://i.imgur.com/ED5fa.png', 'http://i.imgur.com/gJncN.png', 'http://i.imgur.com/3pKFS.png',
                        'http://i.imgur.com/3pKFS.png');

    $u = User::create(array('email' => 'vendor'.self::$vendor_count.'@example.com',
                            'password' => 'password'));

    $v = new Vendor(array('company_name' => $faker->company,
                          'contact_name' => $faker->name,
                          'address' => $faker->streetAddress,
                          'city' => $faker->city,
                          'state' => $faker->stateAbbr,
                          'zip' => $faker->postcode,
                          'ballpark_price' => rand(1,4),
                          'image_url' => $image_urls[array_rand($image_urls)],
                          'homepage_url' => $faker->url,
                          'more_info' => $faker->paragraph));

    $v->user_id = $u->id;
    $v->save();

    foreach (Service::all() as $service) {
      if (rand(1,2) == 2) $v->services()->attach($service->id);
    }

    self::$vendor_count++;

    return $v;
  }

  public static function officer() {
    $faker = Faker\Factory::create();



    $u = User::create(array('email' => 'officer'.self::$officer_count.'@example.gov',
                            'password' => 'password'));

    $o = Officer::create(array('user_id' => $u->id,
                               'phone' => $faker->phoneNumber,
                               'fax' => $faker->phoneNumber,
                               'name' => $faker->firstName . " " . $faker->lastName,
                               'title' => (rand(1,2) == 1) ? "Contracting Officer" : "Program Officer",
                               'agency' => self::$agencies[array_rand(self::$agencies)]));

    $o->verify_with_solnbr("SEED");
    $o->save();

    self::$officer_count++;

    return $o;
  }

  public static function project($fork_from_project_id) {
    $faker = Faker\Factory::create();

    $original_project = Project::find($fork_from_project_id);

    $due_at = new \DateTime();
    $due_at->setTimestamp(rand(1346475600, 1364792400));

    $p = Project::create(array('forked_from_project_id' => $original_project->id,
                               'project_type_id' => $original_project->project_type_id,
                               'title' => self::$project_titles[array_rand(self::$project_titles)],
                               'fbo_solnbr' => rand(0,1) == 0 ? 'SEED-DATA' : null,
                               'agency' => self::$agencies[array_rand(self::$agencies)],
                               'office' => self::$offices[array_rand(self::$offices)],
                               'background' => $faker->paragraph,
                               'sections' => $original_project->sections,
                               'variables' => $original_project->variables,
                               'deliverables' => $original_project->deliverables,
                               'proposals_due_at' => $due_at
                               ));

    return $p;
  }

  public static function bid($attributes = array(), $project_id = false) {
    $faker = Faker\Factory::create();

    $p = $project_id ? Project::find($project_id) : Project::order_by(\DB::raw('RAND()'))->first();
    $v = Vendor::order_by(\DB::raw('RAND()'))->first();

    $prices = array();
    foreach ($p->parsed_deliverables() as $d) {
      $prices[$d] = rand(100, 10000);
    }

    $b = new Bid(array('project_id' => $p->id,
                       'approach' => $faker->paragraph,
                       'previous_work' => $faker->paragraph,

                       // Not using faker because we need some real names in here.
                       'employee_details' => "Adam Becker will be the cat wrangler, ".
                                             "Clay Johnson will manage the Banana stand, ".
                                             "and Jed Wood will provide aerial support.",

                       'prices' => $prices));


    $b->starred = rand(0,1);
    $b->vendor_id = $v->id;
    $b->save();

    if (rand(0,6) === 0) {
      $b->delete_by_vendor();
    } else {
      if (rand(0,1) === 0) {
        $submitted_at = new \DateTime;
        $b->submitted_at = (rand(0,1) === 0) ? $submitted_at : null;
        $b->submit();

        // Dismiss 1/3 of the bids
        if (rand(0,2) === 0) {
          $b->dismiss(Bid::$dismissal_reasons[array_rand(Bid::$dismissal_reasons)], $faker->paragraph(2));
          // Un-dismiss 1/2 of these
          if (rand(0,1) === 0) $b->undismiss();
        }
      }
    }

  }

  public static function question() {
    $faker = Faker\Factory::create();

    $p = Project::order_by(\DB::raw('RAND()'))->first();
    $v = Vendor::order_by(\DB::raw('RAND()'))->first();

    $q = new Question(array('project_id' => $p->id,
                            'question' => $faker->paragraph(3)));

    // Answer 1/2 of the questions
    if (rand(0,1) === 0) {
      $q->answer = (rand(0,1) === 0) ? $faker->sentence : $faker->paragraph;
      $q->answered_by = $p->owner()->id;
    }

    $q->vendor_id = $v->id;
    $q->save();
  }
}