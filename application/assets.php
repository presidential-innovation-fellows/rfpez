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
         // Vendor scripts
  $basset->add('bootstrapjs', 'js/vendor/bootstrap.js')
         ->add('bootstrap-datepicker', 'js/vendor/bootstrap-datepicker.js')
         ->add('wysihtml5', 'js/vendor/wysihtml5.min.js')
         ->add('bootstrap-wysihtml5', 'js/vendor/bootstrap-wysihtml5.js')
         ->add('jquery-sortable', 'js/vendor/jquery.sortable.js')
         ->add('autogrow-input', 'js/vendor/autogrow-input.js')
         ->add('jquerytimeago', 'js/vendor/jquery.timeago.js')
         ->add('jqueryvalidate', 'js/vendor/jquery.validate.js')
         ->add('jqueryvalidaterfpez', 'js/vendor/jquery.validate_rfpez.js')
         ->add('jqueryform', 'js/vendor/jquery.form.js')
         ->add('jqueryhotkeys', 'js/vendor/jquery.hotkeys.js')
         ->add('jquerypjax', 'js/vendor/jquery.pjax.js')

         // Application scripts
         ->add('flashbutton', 'js/flash-button.js')
         ->add('main', 'js/main.js')
         ->add('question-and-answer', 'js/question-and-answer.js')
         ->add('vendor-image-preview', 'js/vendor-image-preview.js')
         ->add('new-bid', 'js/new-bid.js')
         ->add('validation', 'js/validation.js')
         ->add('filter-projects', 'js/filter-projects.js')
         ->add('collaborators', 'js/collaborators.js')
         ->add('sow-composer', 'js/sow-composer.js')
         ->add('notifications', 'js/notifications.js')
         ->add('dsbs-lookup', 'js/dsbs-lookup.js')
         ->add('infinite-vendor-scroll', 'js/infinite-vendor-scroll.js')
         ->add('save-bid-draft', 'js/save-bid-draft.js')

         // backbone!
         ->add('underscore', 'js/vendor/underscore.js')
         ->add('backbone', 'js/vendor/backbone.js')
         ->add('comments-backbone', 'js/comments-backbone.js')
         ->add('collaborators-backbone', 'js/collaborators-backbone.js')

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
