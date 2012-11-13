<?php

$I = new WebGuy($scenario);

$I->amOnPage('/signin');

/* Officer login succeeds */
$I->fillField('email','officer1@example.gov');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('My Projects', 'h4');

$I->amOnPage("/notifications");
$I->see("has submitted a bid", ".line1");

$I->click("submitted a bid");
$I->see("Bid from", "h1");
$I->click("(view profile)");

$I->see("Contact Name");
$I->see("SAM.gov");
$I->see("DSBS");

$I->click('Projects');
$I->see('My Projects', 'h4');

