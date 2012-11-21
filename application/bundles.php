<?php

/*
|--------------------------------------------------------------------------
| Bundle Configuration
|--------------------------------------------------------------------------
|
| Bundles allow you to conveniently extend and organize your application.
| Think of bundles as self-contained applications. They can have routes,
| controllers, models, views, configuration, etc. You can even create
| your own bundles to share with the Laravel community.
|
| This is a list of the bundles installed for your application and tells
| Laravel the location of the bundle's root directory, as well as the
| root URI the bundle responds to.
|
| For example, if you have an "admin" bundle located in "bundles/admin"
| that you want to handle requests with URIs that begin with "admin",
| simply add it to the array like this:
|
|		'admin' => array(
|			'location' => 'admin',
|			'handles'  => 'admin',
|		),
|
| Note that the "location" is relative to the "bundles" directory.
| Now the bundle will be recognized by Laravel and will be able
| to respond to requests beginning with "admin"!
|
| Have a bundle that lives in the root of the bundle directory
| and doesn't respond to any requests? Just add the bundle
| name to the array and we'll take care of the rest.
|
*/

return array(

  'bob',
  'jaded' => array(
  	'auto' => true
  ),
  'feeder' => array(
    // when the bundle is started all Feeder classes are automatically loaded
    // so you can either autostart it or have autoloader mappings (more efficient).
    //'auto' => true,

    'autoloads' => array(
      'map' => array(
        'Feed' => '(:bundle)/chained.php',

        'FeedChannel' => '(:bundle)/feeder.php',
        'FeedEntry' => '(:bundle)/feeder.php',
        'Feeder' => '(:bundle)/feeder.php',
        'TextFeeder' => '(:bundle)/feeder.php',
        'FeedOut' => '(:bundle)/feeder.php',
      ),
    ),
  )

);