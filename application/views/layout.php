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

  <?= Section::yield('content') ?>

  <!-- <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script> -->
  <script>window.jQuery || document.write('<script src="/js/jquery-1.8.1.min.js"><\/script>')</script>

</body>
</html>