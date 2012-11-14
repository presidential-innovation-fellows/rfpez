<?php

$I = new WebGuy($scenario);

/* Vendor login with wrong password fails */
$I->amOnPage('/signin');
$I->fillField('email','vendor1@example.com');
$I->fillField('password','wrongpassword');
$I->click('button.btn');
$I->see('Login incorrect', '.alert');


$I = new WebGuy($scenario);

/* Vendor login succeeds */
$I->fillField('email','vendor1@example.com');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('Projects', 'h4');

/* Signout works */
$I->click('Sign Out');
$I->see('A Technology Marketplace That Everybody Loves');

/* Officer login wrong password fails */
$I->amOnPage('/signin');
$I->fillField('email','officer1@example.gov');
$I->fillField('password','wrongpassword');
$I->click('button.btn');
$I->see('Login incorrect', '.alert');

/* Then officer login works */
$I->fillField('email','officer1@example.gov');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('My Projects', 'h4');
