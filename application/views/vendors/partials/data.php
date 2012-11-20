<?php if (!isset($defer)) $defer = false; ?>
<div class="vendor-data">
  <?php echo Jade\Dumper::_html(Helper::datum("Contact Name", $vendor->contact_name)); ?>
  <?php echo Jade\Dumper::_html(Helper::datum("Email", $vendor->user->email, true)); ?>
  <?php echo Jade\Dumper::_html(Helper::datum("Address", $vendor->address."<br />".$vendor->city.", ".$vendor->state." ".$vendor->zip)); ?>
  <?php echo Jade\Dumper::_html(Helper::datum("Website", $vendor->homepage_url, true)); ?>
  <?php echo Jade\Dumper::_html(Helper::datum("Portfolio", $vendor->portfolio_url, true)); ?>
  <?php echo Jade\Dumper::_html(Helper::datum("Source code", $vendor->sourcecode_url, true)); ?>
  <div class="datum">
    <label>SAM.gov</label>
    <div class="content">
      <?php if ($vendor->sam_entity_name): ?>
        <span class="green">Yes, under "<?php echo Jade\Dumper::_text($vendor->sam_entity_name); ?>"</span>
      <?php else: ?>
        <span class="red">No</span>
      <?php endif; ?>
    </div>
  </div>
  <div class="datum">
    <label>DSBS</label>
    <div class="content">
      <?php if ($vendor->dsbs_name): ?>
        <span class="green">Yes, under "<?php echo Jade\Dumper::_text($vendor->dsbs_name); ?>"
</span>
        <?php echo Jade\Dumper::_html(View::make('vendors.partials.dsbs_certifications')->with('user_id', $vendor->dsbs_user_id)->with('defer', $defer)); ?>
      <?php else: ?>
        <span class="red">No</span>
      <?php endif; ?>
    </div>
  </div>
  <div class="datum">
    <label>EPLS</label>
    <div class="content">
      <?php if ($vendor->epls): ?>
        <span class="red">
          <a href="http://rfpez-apis.presidentialinnovationfellows.org/exclusions?duns=<?php echo Jade\Dumper::_text($vendor->duns); ?>" target="_blank">Yes</a>
        </span>
      <?php else: ?>
        <span class="green">No</span>
      <?php endif; ?>
    </div>
  </div>
</div>