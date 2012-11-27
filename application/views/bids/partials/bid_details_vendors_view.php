<div class="details-inner">
  <?php if ($bid->dismissed()): ?>
    <div class="alert alert-danger dismissed-alert">
      <div class="dismissal-notice">Your bid has been dismissed.</div>
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
  <h5>Approach</h5>
  <p><?php echo Jade\Dumper::_html($bid->approach); ?></p>
  <h5>Previous Work</h5>
  <p><?php echo Jade\Dumper::_html($bid->previous_work); ?></p>
  <h5>Employee Details</h5>
  <p><?php echo Jade\Dumper::_html($bid->employee_details); ?></p>
  <div class="row">
    <div class="span6 prices">
      <h5>Prices</h5>
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
              <td>$<?php echo Jade\Dumper::_text($price); ?><?php echo Jade\Dumper::_text($bid->project->price_type == Project::PRICE_TYPE_HOURLY ? '/hr' : ''); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($bid->project->price_type == Project::PRICE_TYPE_FIXED): ?>
          <tfoot>
            <tr class="info">
              <td>Total Price</td>
              <td><?php echo Jade\Dumper::_text($bid->display_price()); ?></td>
            </tr>
          </tfoot>
        <?php endif; ?>
      </table>
    </div>
    <div class="span6 example-work">
      <h5>Example Work</h5>
      <div class="vendor-image-preview-frame">
        <img src="<?php echo Jade\Dumper::_text($bid->vendor->image_url); ?>" />
      </div>
    </div>
  </div>
  <?php if (!$bid->dismissed_at && !$bid->awarded_at): ?>
    <a href="<?php echo Jade\Dumper::_text(route('bid_destroy', array($bid->project->id, $bid->id))); ?>" data-confirm="Are you sure you want to delete your bid?">
      Delete Bid
    </a>
  <?php endif; ?>
</div>