<?php if (!isset($defer)) $defer = true; ?>
<div class="details-inner">
  <?php if ($bid->dismissed()): ?>
    <div class="alert alert-danger dismissed-alert">
      <div class="dismissal-notice">You have dismissed this bid.</div>
      <div class="dismissal-reason">
        <strong>Reason:</strong>
        <span><?php echo Jade\Dumper::_text($bid->dismissal_reason); ?></span>
      </div>
      <div class="dismissal-explanation">
        <strong>Explanation:</strong>
        <span><?php echo Jade\Dumper::_text($bid->dismissal_explanation); ?></span>
      </div>
    </div>
  <?php endif; ?>
  <div class="row-fluid">
    <div class="span5 col1">
      <h5>Approach</h5>
      <p><?php echo Jade\Dumper::_html($bid->approach); ?></p>
      <h5>Previous Work</h5>
      <p><?php echo Jade\Dumper::_html($bid->previous_work); ?></p>
      <h5>Employee Details
</h5>
      <?php echo Jade\Dumper::_html(View::make('vendors.partials.epls_results')->with('bid', $bid)); ?>
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
    <div class="span5 col2">
      <h5>
        <?php echo Jade\Dumper::_text($bid->vendor->company_name); ?>
        <a href="<?php echo Jade\Dumper::_text(route('vendor', array($bid->vendor->id))); ?>">(view profile)</a>
      </h5>
      <?php echo Jade\Dumper::_html(View::make('vendors.partials.data')->with('vendor', $bid->vendor)->with('defer', true)); ?>
      <h5>Example Work</h5>
      <div class="vendor-image-preview-frame">
        <img src="<?php echo Jade\Dumper::_text($bid->vendor->image_url); ?>" />
      </div>
    </div>
  </div>
</div>