<?php
require_once 'feeder.php';

$proger = new FeedPerson;
$proger->Name('Proger_XP');
$proger->EMail('proger.xp@gmail.com');
$proger->URL('http://proger.i-forge.net');

$camilo = new FeedPerson;
$camilo->Name('Camilo');
$camilo->URL('http://camilomm.deviantart.com');

$feeder = new Feeder;

$feeder->Channel()->Title()->Add('text', 'My Feed');
$feeder->Channel()->Author(0, $proger);
$feeder->Channel()->Description()->Add('text', 'Just another PHP Feed');
$feeder->Channel()->BaseURL('http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/');

$entry = new FeedEntry;

$entry->Title()->Add('text', 'My first post');
$entry->Updated(strtotime('22 Jan 2011'));
$entry->Author(0, $proger);
$entry->Content()->Add('text', 'Thinking about something to write...');
$entry->Content()->Add('html', 'Thinking about <em>something</em> to write&hellip;');

$feeder->Entry(0, $entry);

$entry = new FeedEntry;

$entry->Title()->Add('text', 'Another filler');
$entry->Updated(strtotime('23 May 2012'));
$entry->Author(0, $proger);
$entry->Contributor(0, $camilo);
$entry->Content()->Add('html', 'Why? Because he\'s my friend <img src="smile.png" />');

$feeder->Entry(1, $entry);

$output = new AtomFeed;
$output->Output($feeder);

