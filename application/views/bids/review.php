<?php Section::inject('page_title', 'Review Bids'); ?>
<?php Section::start('content') ?>

<h3>Review Bids for <?= $contract->title ?></h3>

<?= View::make('bids.dismiss_modal') ?>

<?php foreach($bids as $bid): ?>
  <div class="bid">
    $<?= intval($bid->total_price()) ?> - <?= $bid->vendor->company_name ?> |
    <a href="<?= route('bid', array($contract->id, $bid->id)) ?>">details</a>

    | <a href="#" class="show-dismiss-modal" data-contract-id="<?= $contract->id ?>"
         data-bid-id="<?= $bid->id ?>" data-vendor-company-name="<?= $bid->vendor->company_name ?>"
         data-remove-from-list="bid">Dismiss?</a>

  </div>
<?php endforeach; ?>

<?php Section::stop() ?>
