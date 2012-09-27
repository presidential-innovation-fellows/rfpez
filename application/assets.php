<?php

/*
|--------------------------------------------------------------------------
| Asset Definitions
|--------------------------------------------------------------------------
*/

Bundle::start('basset');

if (Config::get('basset')) Basset\Config::extend(Config::get('basset'));

Basset::scripts('website', function($basset)
{
  $basset->add('modernizerjs', 'js/vendor/modernizr-2.6.1-respond-1.1.0.min.js');
  $basset->add('bootstrapjs', 'js/vendor/bootstrap.min.js');
  $basset->add('mainjs', 'main.js');
  //        ->add('sisyphus', 'sisyphus.min.js')
  //        ->add('app', 'app.js', 'pjax');
});

Basset::styles('website', function($basset)
{
  $basset->add('bootstrap', 'bootstrap.css')
         ->add('maincss', 'main.css');
});
