<html>
<head>
  <title><?= $sow->title ?></title>
  <?= HTML::style('css/print.css') ?>
</head>
<body>
  <h1><?= $sow->title ?>
  <?= $sow->body ?>


  <div class="disclaimer"><?= Config::get('sowcomposer.disclaimer') ?></small>
  <script>
    window.print()
  </script>
</body>
</html>