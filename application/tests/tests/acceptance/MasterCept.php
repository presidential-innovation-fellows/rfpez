<?php

if ($scenario->preload()) {
  ### This is where we can put things that happen before the test runs
  ### SEE ALSO: _bootstrap.php where fixtures can (supposedly) be defined
  //$user = new User();
  //$user->name('davert');
  //$user->save();
}

$I = new WebGuy($scenario);
$I->wantTo('Sign up as a new vendor');
$I->amOnPage('/');
$I->click('Register as a Company');
$I->see('New Company');
// TODO fill out the new vendor form and sign up, possibly test errors

$I->wantTo('Log in with the wrong password and see an error');
$I->amOnPage('/signin');
$I->fillField('email','vendor1@example.com');
$I->fillField('password','wrongpassword');
$I->click('button.btn');
$I->see('Login incorrect', '.alert');

$I->wantTo('Log in as a vendor');
$I->fillField('email','vendor1@example.com');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('My Bids', 'h4');

$I->wantTo('Sign out');
$I->click('Sign Out');
$I->see('A Technology Marketplace That Everybody Loves', 'h4');

$I->wantTo('sign in as an officer via the modal');
$I->amOnPage('/');
$I->click('Sign In', 'a');
$I->fillField('#signinModal #email','officer1@example.gov');
$I->fillField('#signinModal input[type=password]','password');
$I->click('#signinModal button.btn-primary');
$I->amOnPage('/projects/mine');
$I->see('My Projects', 'h4');

//$I->click('.my-project-table a');
//$I->see('Timeline', 'h3');


if ($scenario->running()) {
  ### This is where we can put things that happen after the test runs
  //$user->delete();
}

?>