<?php

class Seed_Task {

  public function run() {
    $s1 = Service::create(array('name' => 'Web Design', 'description' => 'This is the description for web design.'));
    $s2 = Service::create(array('name' => 'Web Development', 'description' => 'This is the description for web development.'));
    $s3 = Service::create(array('name' => 'Content Management ', 'description' => 'This is the description for content management.'));

    $ou = User::create(array('email' => 'officer@example.com',
                             'password' => 'password'));

    $vu = User::create(array('email' => 'vendor@example.com',
                             'password' => 'password'));

    $o = Officer::create(array('user_id' => $ou->id,
                               'phone' => '1234567890',
                               'fax' => '1234567890',
                               'name' => 'Gob Bluth',
                               'title' => 'CEO',
                               'agency' => 'Bluth Company'));

    $v = Vendor::create(array('user_id' => $vu->id,
                              'company_name' => "Bluth's Frozen Bananas",
                              'contact_name' => 'George Michael Bluth',
                              'address' => '1234 Main St.',
                              'city' => 'Los Angeles',
                              'state' => 'CA',
                              'zip' => '10101',
                              'ballpark_price' => 1,
                              'image_url' => 'http://i.imgur.com/sHHmT.png',
                              'homepage_url' => 'http://www.google.com',
                              'more_info' => 'There is no more info.'));

    for ($i = 0; $i < 45; $i++) {
      $user = User::create(array('email' => 'vendor'.$i.'@example.com',
                             'password' => 'password'));

      $vendor = Vendor::create(array('user_id' => $user->id,
                              'company_name' => "Bluth's Frozen Bananas #$i",
                              'contact_name' => 'George Michael Bluth',
                              'address' => '1234 Main St.',
                              'city' => 'Los Angeles',
                              'state' => 'CA',
                              'zip' => '10101',
                              'ballpark_price' => 1,
                              'image_url' => 'http://i.imgur.com/sHHmT.png',
                              'homepage_url' => 'http://www.google.com',
                              'more_info' => 'There is no more info.'));
    }


    $v->services()->attach($s1->id);
    $v->services()->attach($s3->id);

    for ($i = 0; $i < 5; $i++) {
      $c = new Contract(    array('agency' => 'Bluth Company',
                                  'office' => 'Office of Small Business Innovation',
                                  'title' => "Website Design for the Bluth Company #$i",
                                  'statement_of_work' => '

                                  <!--
                                    deliverables="Deliverable #1, Deliverable #2"
                                    bgscope="The {{AGENCY}} {{OFFICE}} requires the services of a vendor that can provide a number of different products and/or services related to the strategic design, content creation, analysis and promotion of the overall rebranding of {{WEBSITE}}. Through the rebranding and creative development process, {{AGENCY}} hopes to conduct a comp lete audience analysis to drive external Department messaging, enhance user experience and modernize the {{AGENCY}} web presence to create a sustainable platform to promote {{AGENCY}}s mission and emerge as a leader in government online communication."
                                  -->
                                  <h1>Web Video Development & Production</h1>
                                    <hr />

                                    <h3>Background &amp; Scope</h3>
                                    <p>
                                      Background and scope.  </p>

                                    <h3>Deliverables</h3>
                                        <h4>1) Site Surveys</h4>
                                      <p>
                                                The Contractor shall participate in site-surveys with designated staff from the  to scout proper locations as well as plan to attend face-to-face meetings to discuss the content of each video.          </p>
                                      <h3>Requirements</h3>
                                        <h4>1) Online Portfolio</h4>
                                      <p>
                                                The   requests that you have your portfolio for public review online.          </p>


                                    <h3>Timeline</h3>
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th>Deliverable</th>
                                          <th>Due Date</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                                <tr>
                                            <td>Site Surveys</td>
                                            <td>9/25/12</td>
                                          </tr>
                                            </tbody>
                                    </table>
                                  ',
                                  'set_aside' => 'N/A',
                                  'classification_code' => 'X -- Lease or rental of facilities',
                                  'naics_code' => '541511',
                                  'proposals_due_at' => new \DateTime('February 10th, 2013'),
                                  'posted_at' => new \DateTime));
      $c->fbo_solnbr = 'D-13-ST-0010';
      $c->officer_id = $o->id;
      $c->save();


      $b = new Bid    (array('contract_id' => $c->id,
                             'approach' => 'This is the approach.',
                             'previous_work' => 'This is the previous work.',
                             'employee_details' => 'These are the employee details.',
                             'prices' => array('First deliverable' => '100', 'Second deliverable' => '200')));
      $b->vendor_id = $v->id;
      $b->save();

      $q = new Question(array('contract_id' => $c->id,
                              'question' => 'Is there any need for information architecture? I know that when I usually do a thing like this, information architecture is most definitely included.',
                              'answer' => 'Yes, there is. I would budget about $1300 for it. Yep.'));
      $q->vendor_id = $v->id;
      $q->save();

      $c = new Contract(    array('agency' => 'Server-side French Fries',
                                  'office' => 'Sub-basement fortress',
                                  'title' => "Video Production #$i",
                                  'statement_of_work' => '

                                  <!--
                                    deliverables="Closed-captioning, Information Architecture"
                                    bgscope="The {{AGENCY}} {{OFFICE}} requires the services of a vendor that can provide a number of different products and/or services related to the strategic design, content creation, analysis and promotion of the overall rebranding of {{WEBSITE}}. Through the rebranding and creative development process, {{AGENCY}} hopes to conduct a comp lete audience analysis to drive external Department messaging, enhance user experience and modernize the {{AGENCY}} web presence to create a sustainable platform to promote {{AGENCY}}s mission and emerge as a leader in government online communication."
                                  -->
                                  <h1>Web Video Development & Production</h1>
                                    <hr />

                                    <h3>Background &amp; Scope</h3>
                                    <p>
                                      Background and scope.  </p>

                                    <h3>Deliverables</h3>
                                        <h4>1) Site Surveys</h4>
                                      <p>
                                                The Contractor shall participate in site-surveys with designated staff from the  to scout proper locations as well as plan to attend face-to-face meetings to discuss the content of each video.          </p>
                                      <h3>Requirements</h3>
                                        <h4>1) Online Portfolio</h4>
                                      <p>
                                                The   requests that you have your portfolio for public review online.          </p>


                                    <h3>Timeline</h3>
                                    <table class="table">
                                      <thead>
                                        <tr>
                                          <th>Deliverable</th>
                                          <th>Due Date</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                                <tr>
                                            <td>Site Surveys</td>
                                            <td>9/25/12</td>
                                          </tr>
                                            </tbody>
                                    </table>
                                  ',
                                  'set_aside' => 'N/A',
                                  'classification_code' => 'X -- Lease or rental of facilities',
                                  'naics_code' => '512110',
                                  'proposals_due_at' => new \DateTime('December 9th, 2012'),
                                  'posted_at' => new \DateTime));
      $c->fbo_solnbr = 'V-13-ST-0014';
      $c->officer_id = $o->id;
      $c->save();


      $b = new Bid    (array('contract_id' => $c->id,
                             'approach' => 'This is the approach.',
                             'previous_work' => 'This is the previous work.',
                             'employee_details' => 'These are the employee details.',
                             'prices' => array('First deliverable' => '100', 'Second deliverable' => '200')));
      $b->vendor_id = $v->id;
      $b->submit();


      $q = new Question(array('contract_id' => $c->id,
                              'question' => 'Is there any need for information architecture? I know that when I usually do a thing like this, information architecture is most definitely included.',
                              'answer' => 'Yes, there is. I would budget about $1300 for it. Yep.'));
      $q->vendor_id = $v->id;
      $q->save();
    }
  }

}
