<?php

Class Factory {

  public static $vendor_count = 1;

  public static $officer_count = 1;

  public static $agencies = array("Department of Justice", "Health and Human Services", "Small Business Administration",
                                  "General Services Administration", "Department of Education");

  public static $offices = array("Office of Capital Access", "Office of Credit Risk Management", "Office of Investment",
                                 "Office of Field Operations", "Office of Surety Guarantees", "Office of Hearings & Appeals");

  public static $project_titles = array("Website Design for the Bluth Company");

  public static $solnbrs = array("VA24412Q1868", "VA256-12-Q-2266", "SPM7L413V0156", "N00183-12-T-0557-0001",
                                 "W911N2-12-R-0081", "SPRPA112QX020", "N0038307G002J7371");

  public static function vendor() {
    $faker = Faker\Factory::create();

    $image_urls = array('http://i.imgur.com/Gh4ZX.png', 'http://i.imgur.com/vySFV.png', 'http://i.imgur.com/RdBae.png',
                        'http://i.imgur.com/ED5fa.png', 'http://i.imgur.com/gJncN.png', 'http://i.imgur.com/3pKFS.png',
                        'http://i.imgur.com/3pKFS.png');

    $u = User::create(array('email' => 'vendor'.self::$vendor_count.'@example.com',
                            'password' => 'password'));

    $v = Vendor::create(array('user_id' => $u->id,
                              'company_name' => $faker->company,
                              'contact_name' => $faker->name,
                              'address' => $faker->streetAddress,
                              'city' => $faker->city,
                              'state' => $faker->stateAbbr,
                              'zip' => $faker->postcode,
                              'ballpark_price' => rand(1,4),
                              'image_url' => $image_urls[array_rand($image_urls)],
                              'homepage_url' => $faker->url,
                              'more_info' => $faker->paragraph));

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

    self::$officer_count++;

    return $o;
  }

  public static function project() {
    $faker = Faker\Factory::create();

    $o = Officer::order_by(\DB::raw('RAND()'))->first();

    $naics = array_keys(Project::$naics_codes);

    $due_at = new \DateTime();
    $due_at->setTimestamp(rand(1346475600, 1364792400));
    $posted_at = new \DateTime();
    $posted_at->setTimestamp(rand(1333256400, 1346475600));

    ob_start();
    require("./application/tasks/seed_data/sows/".rand(1,1).".php");
    $contents = ob_get_contents();
    ob_end_clean();

    $p = new Project(array('agency' => $o->agency,
                           'office' => self::$offices[array_rand(self::$offices)],
                           'title' => self::$project_titles[array_rand(self::$project_titles)],
                           'body' => $contents,
                           'naics_code' => $naics[array_rand($naics)],
                           'proposals_due_at' => $due_at));

    $p->fbo_solnbr = self::$solnbrs[array_rand(self::$solnbrs)];
    $p->save();
    $p->officers()->attach($o->id, array('owner' => true));

    $template = SowTemplate::where_title("Web Design")->where_visible(true)->first();

    $s = Sow::create(array('project_id' => $p->id,
                           'body' => $contents,
                           'based_on_sow_template_id' => $template->id,
                           'variables' => array("Deliverable Due Dates" => array("Page Templating" => "10/12/12"),
                                                "WEBSITE" => "energy.gov",
                                                "AGENCY" => "SBA",
                                                "OFFICE" => "OCPL") ));

    SowSection::create(array('sow_id' => $s->id,
                             'display_order' => 0,
                             'section_type' => 'Background & Scope',
                             'body' => $faker->paragraph));

    $i = 0;
    foreach(SowTemplateSection::where_sow_template_id($template->id)->get() as $template_section) {
      if (rand(0,1) == 0) continue;

      SowSection::create(array('sow_id' => $s->id,
                               'display_order' => $i,
                               'section_type' => $template_section->section_type));

      $i++;
    }


  }

  public static function bid() {
    $faker = Faker\Factory::create();

    $p = Project::order_by(\DB::raw('RAND()'))->first();
    $v = Vendor::order_by(\DB::raw('RAND()'))->first();

    $prices = array();
    foreach ($p->parsed_deliverables as $d) {
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
    $b->deleted_by_vendor = (rand(0,6) === 0) ? true : false;
    $b->vendor_id = $v->id;
    $b->save();

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

  public static function question() {
    $faker = Faker\Factory::create();

    $p = Project::order_by(\DB::raw('RAND()'))->first();
    $v = Vendor::order_by(\DB::raw('RAND()'))->first();

    $q = new Question(array('project_id' => $p->id,
                            'question' => $faker->paragraph(3)));

    // Answer 1/2 of the questions
    if (rand(0,1) === 0) $q->answer = (rand(0,1) === 0) ? $faker->sentence : $faker->paragraph;

    $q->vendor_id = $v->id;
    $q->save();
  }
}