<?php

$I = new WebGuy($scenario);

$I->amOnPage('/signin');

/* Vendor login succeeds */
$I->fillField('email','vendor1@example.com');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('Projects', 'h4');
$I->click("Sign Out");


/* Sanity-check vendor/new */
$I->amOnPage('/');
$I->click('Register as a Business');
$I->see('New Company');


$email = 'vendor'.time().'@test.com';
$vendorName = 'test'.time();

$I->fillField('vendor[more_info]', 'test');
$I->fillField('vendor[homepage_url]', 'http://test.com');
$I->fillField('vendor[image_url]', 'http://test.com');
$I->fillField('user[email]', $email);
$I->fillField('user[password]', 'password');
$I->fillField('vendor[company_name]', $vendorName);
$I->fillField('vendor[contact_name]', 'test');
$I->fillField('vendor[address]', 'test');
$I->fillField('vendor[city]', 'test');
$I->fillField('vendor[zip]', '01234');
$I->click('Create Profile');
$I->see($email, 'a.dropdown-toggle');


$I->click('Bids');
$I->see('My Bids');

$I->click('Account Settings');
$I->see('My Account');

$I->click('Projects');
$I->see('Projects', 'h4');

$I->click('API for SBA.gov Dynamic Small Business Search');
$I->see('Bids due in', 'h5');


/* Ask a qusetion */
$question = 'This is my question '.time();

$I->submitForm('#ask-question-form', array('project_id' => $I->grabValueFrom("input[name=project_id]"),
                                           'question' => $question));
$I->moveBack();
$I->reloadPage();
$I->see($question, '.question');


$I->click("Bid on this Contract");
$I->see("New Bid", "h5");

$I->fillField("bid[approach]", "Approach.");
$I->fillField("bid[previous_work]", "Previous work.");
$I->fillField("bid[employee_details]", "Employee details.");
$I->click("Submit Bid");

$I->see("Thanks for submitting your bid.");

$I->see("Approach.", 'p');
$I->see("Previous work.", 'p');
$I->see("Employee details.", 'p');


$I->click("Sign Out");


/** Officer Sanity **/

$I = new WebGuy($scenario);

$I->amOnPage('/signin');

/* Officer login succeeds */
$I->fillField('email','officer1@example.gov');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('My Projects', 'h4');

$I->amOnPage("/notifications");
$I->see("$vendorName has submitted a bid", ".line1");

$I->click("submitted a bid");
$I->see("Bid from", "h1");
$I->click("(view profile)");

$I->see("Contact Name");
$I->see("SAM.gov");
$I->see("DSBS");

$I->click('Projects');
$I->see('My Projects', 'h4');

$I->click('API for SBA.gov Dynamic Small Business Search');
$I->see('Bids awaiting review', 'h5');
$I->see($vendorName, '.bid[data-vendor-company-name='.$vendorName.']');

$I->click('a[href*=comments]');
$I->see('Add Comment', 'h5');

$I->click('Admin');
$I->see('Update Project');

$I->click('Vendors');
$I->see('Browse Vendors', 'h4');