<?php Section::inject('page_title', $contract->title); ?>
<?php Section::start('content') ?>

<h3><?= $contract->title ?></h3>

<?= $contract->statement_of_work ?>

<div class="vendor-only">
  <?php if (Auth::user() && Auth::user()->is_vendor() && $bid = $contract->current_bid_from(Auth::user()->vendor)): ?>
    <a href="<?= route('bid', array($contract->id, $bid->id)) ?>">View my bid</a>
  <?php else: ?>
    <a href="<?= route('new_bids', array($contract->id)) ?>">Bid on this Contract</a>
  <?php endif; ?>
</div>

<?php Section::stop() ?>
