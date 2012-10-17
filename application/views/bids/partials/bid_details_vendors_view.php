<div class="details-inner">
  <?php if ($bid->dismissed()): ?>
    <div class="alert alert-danger dismissed-alert">
      <div class="dismissal-notice">Your bid has been dismissed.</div>
      <div class="dismissal-reason">
        <strong>Reason:</strong>
        <span><?php echo Jade\Dumper::_text($bid->dismissal_reason); ?></span>
      </div>
      <div class="dismissal-explanation">
        <strong>Explanation:</strong>
        <span><?php echo Jade\Dumper::_text($bid->dismissal_explanation); ?></span>
      </div>
    </div>
  <?php elseif (!$bid->awarded_at): ?>
    <div class="alert alert-info">
      Your bid is currently being reviewed. We'll let you know when the status changes.
    </div>
  <?php else: ?>
    <div class="alert alert-success">
      <strong>Your bid won!</strong>
      <?php if (trim($bid->awarded_message) != ""): ?>
        Here's what the government officer said:
        <br /><br />
        <em>"<?php echo Jade\Dumper::_text($bid->awarded_message); ?>"</em>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <h4>Approach</h4>
  <p><?php echo Jade\Dumper::_text($bid->approach); ?></p>
  <h4>Previous Work</h4>
  <p><?php echo Jade\Dumper::_text($bid->previous_work); ?></p>
  <h4>Employee Details</h4>
  <p><?php echo Jade\Dumper::_text($bid->employee_details); ?></p>
  <div class="row">
    <div class="span6 prices">
      <h4>Prices</h4>
      <table class="table">
        <thead>
          <tr>
            <th>Deliverable</th>
            <th>Price</th>
          </tr>
        </thead>
        <?php if ($bid->prices): ?>
          <?php foreach($bid->prices as $deliverable => $price): ?>
            <tr>
              <td><?php echo Jade\Dumper::_text($deliverable); ?></td>
              <td>$<?php echo Jade\Dumper::_text($price); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        <tfoot>
          <tr class="info">
            <td>Total Price</td>
            <td>$<?php echo Jade\Dumper::_text($bid->total_price()); ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
    <div class="span6 example-work">
      <h4>Example Work</h4>
      <div class="vendor-image-preview-frame">
        <img src="<?php echo Jade\Dumper::_text($bid->vendor->image_url); ?>" />
      </div>
    </div>
  </div>
  <a href="<?php echo Jade\Dumper::_text(route('bid_destroy', array($bid->project->id, $bid->id))); ?>" data-confirm="Are you sure you want to delete your bid?">
    Delete Bid
  </a>
</div>