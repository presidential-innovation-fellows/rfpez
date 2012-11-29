<?php if (!isset($defer)) $defer = true; ?>
<div class="details-inner">
  <?php if ($bid->dismissed()): ?>
    <div class="alert alert-danger dismissed-alert">
      <div class="dismissal-notice">You have dismissed this bid.</div>
      <div class="dismissal-reason">
        <strong>Reason:</strong>
        <span><?php echo $bid->dismissal_reason; ?></span>
      </div>
      <div class="dismissal-explanation">
        <strong>Explanation:</strong>
        <span><?php echo $bid->dismissal_explanation; ?></span>
      </div>
    </div>
  <?php endif; ?>
  <div class="row-fluid">
    <div class="span5 col1">
      <h5>Approach</h5>
      <p><?php echo $bid->approach; ?></p>
      <h5>Previous Work</h5>
      <p><?php echo $bid->previous_work; ?></p>
      <h5>Employee Details
</h5>
      <?php echo View::make('vendors.partials.epls_results')->with('bid', $bid); ?>
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
              <td><?php echo $deliverable; ?></td>
              <td>$<?php echo $price; ?><?php echo $bid->project->price_type == Project::PRICE_TYPE_HOURLY ? '/hr' : ''; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
        <?php if ($bid->project->price_type == Project::PRICE_TYPE_FIXED): ?>
          <tfoot>
            <tr class="info">
              <td>Total Price</td>
              <td><?php echo $bid->display_price(); ?></td>
            </tr>
          </tfoot>
        <?php endif; ?>
      </table>
    </div>
    <div class="span5 col2">
      <h5>
        <?php echo $bid->vendor->company_name; ?>
        <a href="<?php echo route('vendor', array($bid->vendor->id)); ?>">(view profile)</a>
      </h5>
      <?php echo View::make('vendors.partials.data')->with('vendor', $bid->vendor)->with('defer', $defer); ?>
      <h5>Example Work</h5>
      <div class="vendor-image-preview-frame">
        <img src="<?php echo $bid->vendor->image_url; ?>" />
      </div>
    </div>
  </div>
</div>