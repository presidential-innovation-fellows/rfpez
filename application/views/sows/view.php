<?php Section::start('content') ?>

<div class="step step-7">

  <h1><?= $sow->title ?></h1>
  <hr />

  <?= $sow->body ?>

  <div class="disclaimer">
    <?= Config::get('sowcomposer.disclaimer') ?>
  </div>

</div>

<?php Section::stop() ?>
