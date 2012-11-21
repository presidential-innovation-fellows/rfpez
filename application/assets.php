<?php

/*
|--------------------------------------------------------------------------
| Asset Definitions
|--------------------------------------------------------------------------
*/

Bundle::start('basset');

if (Config::get('basset')) Basset\Config::extend(Config::get('basset'));

Basset::scripts('global', function ($b){
  $b->add('js/vendor/bootstrap.js')
    ->add('js/vendor/jquery.validate.js')
    ->add('js/vendor/jquery.validate_rfpez.js')
    ->add('js/vendor/jquery.timeago.js')
    ->add('js/vendor/jquery.form.js')
    ->add('js/vendor/jquery.pjax.js')
    ->add('js/vendor/jquery.placeholder.js')
    ->add('js/flash-button.js')
    ->add('js/main.js')
    ->add('js/question-and-answer.js')
    ->add('js/validation.js')
    ->add('js/filter-projects.js')
    ->add('js/notifications.js')
    ->add('js/dsbs-lookup.js')
    ->add('js/infinite-vendor-scroll.js')
    ->add('js/vendor/underscore.js')
    ->add('js/vendor/backbone.js')
    ->compress();
});

Basset::scripts('vendor', function ($b){
  $b->add('js/vendor-image-preview.js')
    ->add('js/new-bid.js')
    ->add('js/save-bid-draft.js')
    ->compress();
});

Basset::scripts('officer', function ($b){
  $b->add('js/vendor/bootstrap-datepicker.js')
    ->add('js/vendor/wysihtml5.min.js')
    ->add('js/vendor/bootstrap-wysihtml5.js')
    ->add('js/vendor/jquery.sortable.js')
    ->add('js/vendor/autogrow-input.js')
    ->add('js/vendor/jquery.hotkeys.js')
    ->add('js/collaborators.js')
    ->add('js/sow-composer.js')
    ->add('js/comments-backbone.js')
    ->add('js/collaborators-backbone.js')
    ->add('js/sow-deliverables-backbone.js')
    ->compress();
});

Basset::scripts('admin', function ($b){
  $b->add('js/admin-officers-backbone.js')
    ->add('js/admin-projects-backbone.js')
    ->compress();
});


Basset::styles('website', function($basset)
{
  $basset->add('bootstrap', 'bootstrap.css')
  ->add('bootstrap-responsive', 'bootstrap-responsive.css')
  ->add('bootstrap-wysihtml5', 'bootstrap-wysihtml5.css')
  ->add('datepicker', 'datepicker.css')
  ->add('maincss', 'main.css')
  ->compress();
});
