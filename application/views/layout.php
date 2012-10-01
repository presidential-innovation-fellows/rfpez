<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width">

  <title><?= Helper::full_title(Section::yield('page_title')) ?></title>

  <?= Basset::show('website.css') ?>

<?php
  if (Auth::guest()) {
    $body_class = "no-auth";
    print "<style>.only-user { display: none; }</style>";
  } else {
    $body_class = "auth " . (Auth::user()->vendor ? "vendor" : "officer");
    print "<style>.only-user:not(.only-user-".Auth::user()->id.") { display: none; }</style>";
  }
?>
  <?= HTML::script('js/vendor/modernizr-2.6.1-respond-1.1.0.min.js') ?>
</head>
<body class="<?= $body_class ?>">

  <div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
      <div class="container">
        <a class="brand" href="<?= route('root') ?>">EasyBid</a>

        <?= View::make('partials.topnav') ?>

      </div>
    </div>
  </div>

  <div class="container">

    <div id="signinModal" class="modal hide">
      <div class="modal-header">
        <button class="close" type='button' data-dismiss='modal'> x </button>
        <h3>Login to EasyBid</h3>
      </div>
      <?= View::make('partials.signin_form') ?>
    </div>

    <?php if (Session::has('errors')): ?>
      <div class="alert alert-error">
        <ul>
          <?php foreach(Session::get('errors') as $error): ?>
            <li><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <?php if (Session::has('notice')): ?>
      <div class="alert alert-success">
        <?= Session::get('notice') ?>
      </div>
    <?php endif; ?>

    <?= $content ?>

  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.1.min.js"><\/script>')</script>
  <?= Basset::show('website.js') ?>

  <?= Section::yield('additional_scripts') ?>

</body>
</html>