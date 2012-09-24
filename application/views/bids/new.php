<?php Section::inject('page_title', 'New Bid'); ?>
<?php Section::start('content') ?>

<h3><?= $contract->title ?></h3>
<?= $contract->statement_of_work ?>

<hr />

<h3>New Bid</h3>

<form action="<?= route('bids', array($contract->id)) ?>" method="POST">

  <textarea name="bid[approach]" placeholder="Approach"></textarea><br />
  <textarea name="bid[previous_work]" placeholder="Previous Work"></textarea><br />
  <textarea name="bid[other_notes]" placeholder="Other Notes"></textarea><br /><br />

  <h4>Prices</h4>

  <table class="prices-table" border="1">
    <thead>
      <tr>
        <th>Deliverable</th>
        <th>Price</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr class="deliverables-row">
        <td><input type="text" name="deliverable_names[]" /></td>
        <td><input type="text" name="deliverable_prices[]" /></td>
        <td><a href="#" class="remove-deliverable">(x)</a></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="3">
          <input type="button" value="Add Deliverable" id="add-deliverable-button" />
        </td>
      </tr>
    </tfoot>
  </table>

  <br /><br />

  <input type="submit" />

</form>

<?php Section::stop() ?>
