<?php Section::inject('page_title', 'Contracts'); ?>
<?php Section::start('content') ?>

<h3>Contracts</h3>

<?php foreach($contracts as $contract): ?>
  <a href="<?= route('contract', array($contract->id)) ?>"><?= $contract->id ?></a>

  <br />
<?php endforeach; ?>

<?php Section::stop() ?>
