<?php Section::inject('page_title', 'Review Bids'); ?>
<?php Section::start('content') ?>

<h3>Review Bids for <?= $contract->title ?></h3>

<?php foreach($bids as $bid): ?>
  $<?= intval($bid->total_price()) ?> - <?= $bid->vendor->company_name ?> |
  <a href="<?= route('bid', array($contract->id, $bid->id)) ?>">details</a><br />
<?php endforeach; ?>

<?php Section::stop() ?>
