<?php

$I = new WebGuy($scenario);
$I->wantTo('sign in as an officer');
$I->amOnPage('/signin');
$I->fillField('email','officer1@example.gov');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('My Contracts', 'h4');
?>