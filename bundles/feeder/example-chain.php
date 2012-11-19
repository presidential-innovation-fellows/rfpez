<?php
require_once 'chained.php';

Feed::make()  ->title()->add('text', 'My Feed')->add('html', 'My <em>Feed</em>')->up()
              ->author()->name('Proger_XP')->email('proger.xp@gmail.com')->up()
              ->description()->add('text', 'Just another PHP Feed')->up()
              ->baseurl('http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['REQUEST_URI']).'/')
    ->entry() ->title()->add('text', 'My first post')->up()
              ->updated(strtotime('22 Jan 2011'))
              ->author()->name('Proger_XP')->email('proger.xp@gmail.com')->up()
              ->content()->add('text', 'Thinking about something to write...')
                         ->add('html', 'Thinking about <em>something</em> to write&hellip;')
    ->entry() ->title()->add('text', 'Another filler')->up()
              ->updated(strtotime('23 May 2012'))
              ->author()->name('Proger_XP')->email('proger.xp@gmail.com')->up()
              ->contributor()->name('Camilo')->url('http://camilomm.deviantart.com')->up()
              ->content()->add('html', 'Why? Because he\'s my friend <img src="smile.png" />')
    ->feed()->atom();
