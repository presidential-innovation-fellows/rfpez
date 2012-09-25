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

  <link href="http://netdna.bootstrapcdn.com/bootswatch/2.1.0/cerulean/bootstrap.min.css" media="all" type="text/css" rel="stylesheet">

  <?= Basset::show('website.css') ?>

<?php
  if (Auth::guest()) {
    $body_class = "no-auth";
    print "<style>.only-user { display: none; }</style>";
  } else {
    $body_class = "auth " . (Auth::user()->is_vendor() ? "vendor" : "officer");
    print "<style>.only-user:not(.only-user-".Auth::user()->id.") { display: none; }</style>";
  }
?>
</head>
<body class="<?= $body_class ?>">
  <div class="container">

    <h1><a href="<?= route('root') ?>">EasyBid</a></h1>

    <?php if (Auth::check()): ?>
      logged in as <?= Auth::user()->email ?> (<?= Auth::user()->account_type() ?>). <a href="<?= route('signout') ?>">sign out</a>
      <br />
      <?php if (Auth::user()->is_officer()): ?>
        <a href="<?= route('new_contracts') ?>">new contract</a> | <a href="<?= route('my_contracts') ?>">my contracts</a>
      <?php else: ?>
        <a href="<?= route('contracts') ?>">browse contracts</a>
      <?php endif; ?>


    <?php else: ?>
      <a href="<?= route('signin') ?>">sign in</a> | <a href="<?= route('new_vendors') ?>">new vendor</a>
      | <a href="<?= route('new_officers') ?>">new officer</a>
    <?php endif; ?>

    <hr />

    <?php if (Session::has('errors')): ?>
      <?php foreach(Session::get('errors') as $error): ?>
        <?= $error ?><br />
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if (Session::has('notice')): ?>
      <?= Session::get('notice') ?><br />
    <?php endif; ?>

    <?= Section::yield('content') ?>

  </div>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="/js/vendor/jquery-1.8.1.min.js"><\/script>')</script>
  <?= Basset::show('website.js') ?>

</body>
</html>