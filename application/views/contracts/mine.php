<?php Section::inject('page_title', 'My Contracts'); ?>

<h3>My Contracts</h3>

<?php foreach($contracts as $contract): ?>
  <a href="<?= route('contract', array($contract->id)) ?>"><?= $contract->title ?></a> |
  <a href="<?= route('bids', array($contract->id)) ?>">View Bids</a>

  <br />
<?php endforeach; ?>
