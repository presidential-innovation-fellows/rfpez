<?php Section::inject('page_title', 'Contracts'); ?>

<h3>Contracts</h3>

<?php foreach($contracts as $contract): ?>
  <a href="<?= route('contract', array($contract->id)) ?>"><?= $contract->title ?></a>

  <br />
<?php endforeach; ?>
