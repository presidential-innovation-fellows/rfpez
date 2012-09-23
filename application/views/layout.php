<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

  <title></title>
  <?= Basset::show('website.css') ?>

</head>
<body>

  <h1>EasyBid</h1>

  <?php if (Auth::check()): ?>
    logged in as <?= Auth::user()->email ?>. <a href="<?= route('signout') ?>">sign out</a>
  <?php else: ?>
    <a href="<?= route('signin') ?>">sign in</a>
  <?php endif; ?>

  <hr />

  <?= Section::yield('content') ?>

  <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script> -->
  <script>window.jQuery || document.write('<script src="/js/jquery-1.8.1.min.js"><\/script>')</script>

</body>
</html>