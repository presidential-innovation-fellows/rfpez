<?php Section::inject('page_title', 'Show Bid'); ?>
<?php Section::start('content') ?>

<h3><?= $contract->title ?></h3>
<?= $contract->statement_of_work ?>

<hr />

<h3>View Bid</h3>

<h4>Approach</h4>
<p><?= $bid->approach ?></p>

<h4>Previous Work</h4>
<p><?= $bid->previous_work ?></p>

<h4>Other Notes</h4>
<p><?= $bid->other_notes ?></p>

<h4>Prices</h4>
<table border="1">
  <tr>
    <th>Deliverable</th>
    <th>Price</th>
  </tr>
  <?php if ($bid->prices()): ?>
    <?php foreach($bid->prices() as $deliverable => $price): ?>
      <tr>
        <td><?= $deliverable ?></td>
        <td><?= $price ?></td>
      </tr>
    <?php endforeach; ?>
  <?php endif; ?>
</table>

<?php Section::stop() ?>
