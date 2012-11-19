<?php
return array(
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
);