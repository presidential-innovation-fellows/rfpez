<?php
$I = new WebGuy($scenario);
$I->wantTo('Interact with the details and bids of a contract');
$I->amOnPage('/signin');
$I->fillField('email','officer1@example.gov');
$I->fillField('password','password');
$I->click('button.btn');
$I->see('My Contracts', 'h4');
$I->click('.my-contract-table a');
$I->see('Timeline', 'h3');