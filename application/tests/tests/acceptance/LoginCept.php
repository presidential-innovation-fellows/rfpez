<?php

$I = new WebGuy($scenario);

/* Login with wrong password fails */
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

/* Officer login works */
$I->amOnPage('/');
$I->click('Sign In', 'a');
$I->fillField('#signinModal #email','officer1@example.gov');
$I->fillField('#signinModal input[type=password]','password');
$I->click('#signinModal button.btn-primary');
$I->amOnPage('/projects/mine');
$I->see('My Projects', 'h4');
