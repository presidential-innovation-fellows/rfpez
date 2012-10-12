<?php
$I = new WebGuy($scenario);
$I->wantTo('sign in as an officer via the modal');
$I->amOnPage('/');
$I->click('Sign In', 'a');
$I->fillField('#signinModal #email','officer1@example.gov');
$I->fillField('#signinModal input[type=password]','password');
$I->click('#signinModal button.btn-primary');
$I->amOnPage('/contracts/mine');
$I->see('My Contracts', 'h4');

?>