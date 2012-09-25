<?php Section::inject('page_title', 'Review Bids'); ?>
<?php Section::start('content') ?>

<h3>Review Bids for <?= $contract->title ?></h3>

<?php foreach($bids as $bid): ?>
  <?= $bid->total_price() ?>
<?php endforeach; ?>

<?php Section::stop() ?>
