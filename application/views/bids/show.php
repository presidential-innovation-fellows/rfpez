<?php Section::inject('page_title', 'Show Bid'); ?>

<?php if (Auth::user()->officer): ?>
  <?= View::make('bids.dismiss_modal') ?>
<?php endif; ?>

<h1>View Bid for contract <a href="<?= route('contract', array($contract->id)) ?>"><?= $contract->title ?></a></h1>

<div class="officer-only">
  <?php if ($bid->dismissed()): ?>
    You have dismissed this bid.<br />
    Reason: <?= $bid->dismissal_reason ?><br />
    Explanation: <?= $bid->dismissal_explanation ?>
  <?php else: ?>
    <a href="#" class="show-dismiss-modal" data-contract-id="<?= $contract->id ?>"
       data-bid-id="<?= $bid->id ?>" data-vendor-company-name="<?= $bid->vendor->company_name ?>">Dismiss?</a>
  <?php endif; ?>
</div>

<div class="only-user only-user-<?= $bid->vendor->user->id ?>">
  <a href="<?= route('bid_destroy', array($contract->id, $bid->id)) ?>">Delete Bid</a>
</div>

<h4>Approach</h4>
<p><?= $bid->approach ?></p>

<h4>Previous Work</h4>
<p><?= $bid->previous_work ?></p>

<h4>Employee Details</h4>
<p><?= $bid->employee_details ?></p>

<h4>Prices</h4>
<table border="1">
  <tr>
    <th>Deliverable</th>
    <th>Price</th>
  </tr>
  <?php if ($bid->prices): ?>
    <?php foreach($bid->prices as $deliverable => $price): ?>
      <tr>
        <td><?= $deliverable ?></td>
        <td><?= $price ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>
