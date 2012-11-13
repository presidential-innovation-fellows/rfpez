<?php

/* Officer signup sanity-check */
$I = new WebGuy($scenario);
$I->amOnPage('/officers/new');
$I->fillField('user[email]', 'officer'.time().'@test.com');
$I->fillField('officer[name]', 'Test');
$I->fillField('officer[title]', 'Test');
$I->fillField('officer[agency]', 'Test');
$I->fillField('officer[phone]', 'Test');
$I->click('Submit');
$I->see('Sorry, only .gov email addresses are allowed.');
$I->fillField('user[email]', 'officer'.time().'@test.gov');
$I->click('Submit');
$I->see('Please check your email for a link to finish signup.');
