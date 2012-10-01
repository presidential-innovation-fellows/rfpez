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
  $basset->add('bootstrapjs', 'js/vendor/bootstrap.min.js')
         ->add('mainjs', 'main.js');
  //        ->add('sisyphus', 'sisyphus.min.js')
  //        ->add('app', 'app.js', 'pjax');
});

Basset::styles('website', function($basset)
{
  $basset->add('bootstrap', 'bootstrap.css')
         ->add('maincss', 'main.css');
});
