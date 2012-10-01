<?php Section::inject('page_title', 'Review Bids'); ?>

<h1>Review Bids for <?= $contract->title ?></h1>

<?php if (!Input::get('show_all')): ?>
  <a href="<?= route('bids', array($contract->id)) ?>?show_all=true">show all bids (including dismissed)</a>
<?php else: ?>
  <a href="<?= route('bids', array($contract->id)) ?>">hide dismissed bids</a>
<?php endif; ?>

<?= View::make('bids.dismiss_modal') ?>

<?php foreach($bids as $bid): ?>
  <div class="bid">
    $<?= intval($bid->total_price()) ?> - <?= $bid->vendor->company_name ?> |
    <a href="<?= route('bid', array($contract->id, $bid->id)) ?>">details</a>

    |
    <?php if($bid->dismissed()): ?>
      dismissed
    <?php else: ?>
      <a href="#" class="show-dismiss-modal" data-contract-id="<?= $contract->id ?>"
         data-bid-id="<?= $bid->id ?>" data-vendor-company-name="<?= $bid->vendor->company_name ?>"
         data-remove-from-list="bid">Dismiss?</a>
    <?php endif; ?>

  </div>
<?php endforeach; ?>
