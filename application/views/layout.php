<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <meta name="description" content="" />
  <meta name="viewport" content="width=device-width" />
  <title><?php echo e(Helper::full_title(Section::yield('page_title'), Section::yield('page_action'))); ?>
</title>
  <link href="//fonts.googleapis.com/css?family=Telex" media="all" type="text/css" rel="stylesheet">
  <?php echo Helper::asset('css/all'); ?>
  <?php if (Auth::guest()): ?>
    <?php $body_class = "no-auth"; ?>
    <?php print "<style>.only-user { display: none; }</style>"; ?>
  <?php else: ?>
    <?php $body_class = "auth " . (Auth::user()->vendor ? "vendor" : "officer"); ?>
    <?php print "<style>.only-user:not(.only-user-".Auth::user()->id."), .not-user-".Auth::user()->id." { display: none; }</style>"; ?>
  <?php endif; ?>
  <?php if (Auth::officer() && Auth::officer()->role == Officer::ROLE_ADMIN): ?>
    <?php $body_class .= " admin" ?>
  <?php elseif (Auth::officer() && Auth::officer()->role == Officer::ROLE_SUPER_ADMIN): ?>
    <?php $body_class .= " super-admin" ?>
  <?php endif; ?>
  <?php echo HTML::script('js/modernizr.js'); ?>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script>
    window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.1.min.js"><\/script>')
  </script>
  <?php echo Helper::asset('js/global'); ?>
  <?php if (Auth::user()): ?>
    <?php if (Auth::officer() && Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)): ?>
      <?php echo Helper::asset('js/admin'); ?>
    <?php endif; ?>
    <?php if (Auth::officer()): ?>
      <?php echo Helper::asset('js/officer'); ?>
    <?php else: ?>
      <?php echo Helper::asset('js/vendor'); ?>
    <?php endif; ?>
  <?php endif; ?>
  <?php echo Section::yield('additional_scripts'); ?>
</head>
<body class="<?php echo e($body_class); ?>">
  <!--[if lt IE 8]>
    <p class="chromeframe"><?= __("r.chromeframe_text") ?></p>
  <![endif]-->
  <div id="pjax-container">
    <?php echo View::make('pjaxcontainer')->with('content', $content); ?>
  </div>
  <?php echo View::make('partials.footer'); ?>
  <?php if (Request::is_env('production')) { ?>
    <script src="/js/vendor/google.analytics.js"></script>
    <script src="/js/vendor/jquery.formtimer.js"></script>
    <script>
      $(document).on("ready pjax:success", function() { $("form").formTimer(); });
    </script>
  <?php } ?>
</body>
</html>