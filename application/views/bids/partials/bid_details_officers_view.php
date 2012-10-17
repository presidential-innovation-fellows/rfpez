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
    <div class="span6">
      <h4>Approach</h4>
      <p><?php echo Jade\Dumper::_text($bid->approach); ?></p>
      <h4>Previous Work</h4>
      <p><?php echo Jade\Dumper::_text($bid->previous_work); ?></p>
      <h4>Employee Details</h4>
      <p><?php echo Jade\Dumper::_text($bid->employee_details); ?></p>
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
    <div class="span6">
      <h4><?php echo Jade\Dumper::_text($bid->vendor->company_name); ?></h4>
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
      <h4>Example Work</h4>
      <div class="vendor-image-preview-frame">
        <img src="<?php echo Jade\Dumper::_text($bid->vendor->image_url); ?>" />
      </div>
    </div>
  </div>
</div>