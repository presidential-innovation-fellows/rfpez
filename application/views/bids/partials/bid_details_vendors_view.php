<div class="details-inner">
  <?php if ($bid->dismissed()): ?>
    <div class="alert alert-danger dismissed-alert">
      <div class="dismissal-notice"><?php echo e(__('r.bids.partials.bid_details_vendors_view.dismissed')); ?></div>
    </div>
  <?php elseif (!$bid->awarded_at): ?>
    <div class="alert alert-info"><?php echo e(__('r.bids.partials.bid_details_vendors_view.review')); ?></div>
  <?php else: ?>
    <div class="alert alert-success">
      <?php echo __('r.bids.partials.bid_details_vendors_view.won_header'); ?>
      <?php if (trim($bid->awarded_message) != ""): ?>
        <?php echo __('r.bids.partials.bid_details_vendors_view.won_body', array('message' => $bid->awarded_message)); ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>
  <h5>Approach</h5>
  <p><?php echo nl2br(e($bid->approach)); ?></p>
  <h5>Previous Work</h5>
  <p><?php echo nl2br(e($bid->previous_work)); ?></p>
  <h5>Employee Details</h5>
  <p><?php echo nl2br(e($bid->employee_details)); ?></p>
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
              <td><?php echo e($deliverable); ?></td>
              <td>$<?php echo e($price); ?><?php echo e($bid->project->price_type == Project::PRICE_TYPE_HOURLY ? '/hr' : ''); ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($bid->project->price_type == Project::PRICE_TYPE_FIXED): ?>
          <tfoot>
            <tr class="info">
              <td>Total Price</td>
              <td><?php echo e($bid->display_price()); ?></td>
            </tr>
          </tfoot>
        <?php endif; ?>
      </table>
    </div>
    <div class="span6 example-work">
      <h5>Example Work</h5>
      <div class="vendor-image-preview-frame">
        <img src="<?php echo e($bid->vendor->image_url); ?>" />
      </div>
    </div>
  </div>
  <?php if (!$bid->dismissed_at && !$bid->awarded_at): ?>
    <a href="<?php echo e(route('bid_destroy', array($bid->project->id, $bid->id))); ?>" data-confirm="<?php echo __('r.delete_bid_confirmation'); ?>">
      Delete Bid
    </a>
  <?php endif; ?>
</div>