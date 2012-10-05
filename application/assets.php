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
         ->add('jqueryvalidate', 'js/vendor/jquery.validate.js')
         ->add('jqueryvalidaterfpez', 'js/vendor/jquery.validate_rfpez.js')
         ->add('jqueryform', 'js/vendor/jquery.form.js')
         ->add('validation', 'js/validation.js')
         ->add('filtercontracts', 'js/filter-contracts.js')
         ->add('notifications', 'js/notifications.js')
         ->add('mainjs', 'main.js');
});

Basset::styles('website', function($basset)
{
  $basset->add('bootstrap', 'bootstrap.css')
         ->add('maincss', 'main.css');
});
