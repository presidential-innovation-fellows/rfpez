<?php Section::inject('page_title', $contract->title); ?>
<?php Section::start('content') ?>

<div class="row">
  <div class="span7">
    <h3><?= $contract->title ?></h3>

    <?= $contract->statement_of_work ?>
  </div>
  <div class="span5">
    <h4>Proposals due in X days</h4>
    <span class="vendor-only">
      <?php if (Auth::user() && Auth::user()->is_vendor() && $bid = $contract->current_bid_from(Auth::user()->vendor)): ?>
        <a href="<?= route('bid', array($contract->id, $bid->id)) ?>">View my bid</a>
      <?php else: ?>
        <a href="<?= route('new_bids', array($contract->id)) ?>">Bid on this Contract</a>
      <?php endif; ?>
    </span>
  </div>
</div>
<?php Section::stop() ?>
