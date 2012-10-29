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
      <p><?php echo Jade\Dumper::_text($bid->approach); ?></p>
      <h5>Previous Work</h5>
      <p><?php echo Jade\Dumper::_text($bid->previous_work); ?></p>
      <h5>Employee Details</h5>
      <p><?php echo Jade\Dumper::_text($bid->employee_details); ?></p>
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
      <h5><?php echo Jade\Dumper::_text($bid->vendor->company_name); ?></h5>
      <div>Contact: <?php echo Jade\Dumper::_text($bid->vendor->contact_name); ?></div>
      <div>Email: <a href="mailto:<?php echo Jade\Dumper::_text($bid->vendor->user->email); ?>"><?php echo Jade\Dumper::_text($bid->vendor->user->email); ?></a></div>
      <div>
        SAM.gov:
        <?php if ($bid->vendor->sam_entity_name): ?>
          <span class="green">Yes, under "<?php echo Jade\Dumper::_text($bid->vendor->sam_entity_name); ?>"</span>
        <?php else: ?>
          <span class="red">No</span>
        <?php endif; ?>
      </div>
      <div>
        DSBS:
        <?php if ($bid->vendor->dsbs_name): ?>
          <span class="green">Yes, under "<?php echo Jade\Dumper::_text($bid->vendor->dsbs_name); ?>"
</span>
          <?php echo Jade\Dumper::_html(View::make('vendors.partials.dsbs_certifications')->with('user_id', $bid->vendor->dsbs_user_id)->with('defer', true)); ?>
        <?php else: ?>
          <span class="red">No</span>
        <?php endif; ?>
      </div>
      <h5>Example Work</h5>
      <div class="vendor-image-preview-frame">
        <img src="<?php echo Jade\Dumper::_text($bid->vendor->image_url); ?>" />
      </div>
    </div>
  </div>
</div>