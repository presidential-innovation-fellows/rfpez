<?php Section::inject('page_title', 'Contracts'); ?>

<h1>Contracts</h1>

<?php foreach($contracts as $contract): ?>
  <a href="<?= route('contract', array($contract->id)) ?>"><?= $contract->title ?></a>

  <br />
<?php endforeach; ?>
