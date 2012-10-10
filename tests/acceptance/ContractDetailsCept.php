<?php
$I = new WebGuy($scenario);
$I->wantTo('See the details of a contract');
$I->amOnPage('/contracts/mine');
$I->see('My Contracts');
