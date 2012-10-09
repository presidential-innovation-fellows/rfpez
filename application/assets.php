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
         ->add('flashbutton', 'js/flash-button.js')
         ->add('main', 'js/main.js')
         ->add('question-and-answer', 'js/question-and-answer.js')
         ->add('vendor-image-preview', 'js/vendor-image-preview.js')
         ->add('bid-review', 'js/bid-review.js')
         ->add('new-bid', 'js/new-bid.js')
         ->add('validation', 'js/validation.js')
         ->add('filter-contracts', 'js/filter-contracts.js')
         ->add('collaborators', 'js/collaborators.js')
         ->add('notifications', 'js/notifications.js');
});

Basset::styles('website', function($basset)
{
  $basset->add('bootstrap', 'bootstrap.css')
         ->add('maincss', 'main.css');
});
