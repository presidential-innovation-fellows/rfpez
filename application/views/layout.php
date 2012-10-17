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
  <title><?php echo Jade\Dumper::_text(Helper::full_title(Section::yield('page_title'), Section::yield('page_action'))); ?>
</title>
  <?php echo Jade\Dumper::_html(HTML::style('http://fonts.googleapis.com/css?family=Telex')); ?>
  <?php echo Jade\Dumper::_html(Basset::show('website.css')); ?>
  <?php if (Auth::guest()): ?>
    <?php $body_class = "no-auth"; ?>
    <?php print "<style>.only-user { display: none; }</style>"; ?>
  <?php else: ?>
    <?php $body_class = "auth " . (Auth::user()->vendor ? "vendor" : "officer"); ?>
    <?php print "<style>.only-user:not(.only-user-".Auth::user()->id."), .not-user-".Auth::user()->id." { display: none; }</style>"; ?>
  <?php endif; ?>
  <?php echo Jade\Dumper::_html(HTML::script('js/vendor/modernizr-2.6.1-respond-1.1.0.min.js')); ?>
</head>
<body class="<?php echo Jade\Dumper::_text($body_class); ?>">
  <div class="navbar navbar-static-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="brand" href="<?php echo Jade\Dumper::_text(route('root')); ?>">EasyBid
</a>
        <?php echo Jade\Dumper::_html(View::make('partials.topnav')); ?>
      </div>
    </div>
  </div>
  <div class="container">
    <?php if (Auth::guest()): ?>
      <?php echo Jade\Dumper::_html(View::make('partials.signin_modal')); ?>
    <?php endif; ?>
    <?php if (Session::has('errors')): ?>
      <div class="alert alert-error">
        <ul>
          <?php foreach(Session::get('errors') as $error): ?>
            <li><?php echo Jade\Dumper::_text($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <?php if (Session::has('notice')): ?>
      <div class="alert alert-success"><?php echo Jade\Dumper::_text(Session::get('notice')); ?></div>
    <?php endif; ?>
    <?php if (!Section::yield('no_page_header')) { ?>
      <h4>
        <?php echo Jade\Dumper::_text(Section::yield('page_title')); ?>
        <?php echo Jade\Dumper::_html(Section::yield('inside_header')); ?>
      </h4>
    <?php } ?>
    <?php echo Jade\Dumper::_html(Section::yield('subnav')); ?>
    <?php echo Jade\Dumper::_html($content); ?>
  </div>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script>
    window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.1.min.js"><\/script>')
  </script>
  <?php echo Jade\Dumper::_html(Basset::show('website.js')); ?>
  <?php echo Jade\Dumper::_html(Section::yield('additional_scripts')); ?>
</body>