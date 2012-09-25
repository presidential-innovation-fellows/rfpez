<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

  <title><?= Helper::full_title(Section::yield('page_title')) ?></title>
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

  <h1><a href="<?= route('root') ?>">EasyBid</a></h1>

  <?php if (Auth::check()): ?>
    logged in as <?= Auth::user()->email ?> (<?= Auth::user()->account_type() ?>). <a href="<?= route('signout') ?>">sign out</a>
    <br />
    <?php if (Auth::user()->is_officer()): ?>
      <a href="<?= route('new_contracts') ?>">new contract</a>
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

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="/js/jquery-1.8.1.min.js"><\/script>')</script>
  <?= Basset::show('website.js') ?>

</body>
</html>