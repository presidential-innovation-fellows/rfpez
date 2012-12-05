<?php if (!isset($defer)) $defer = false; ?>
<div class="vendor-data">
  <?php echo Helper::datum("Contact Name", $vendor->contact_name); ?>
  <?php echo Helper::datum("Email", $vendor->user->email, true); ?>
  <?php echo Helper::datum("Address", $vendor->address."<br />".$vendor->city.", ".$vendor->state." ".$vendor->zip); ?>
  <?php echo Helper::datum("Website", $vendor->homepage_url, true); ?>
  <?php echo Helper::datum("Portfolio", $vendor->portfolio_url, true); ?>
  <?php echo Helper::datum("Source code", $vendor->sourcecode_url, true); ?>
  <div class="datum">
    <label>DSBS</label>
    <div class="content">
      <?php if ($vendor->dsbs_name): ?>
        <span class="green">Yes, under "<?php echo e($vendor->dsbs_name); ?>"
</span>
        <?php echo View::make('vendors.partials.dsbs_certifications')->with('user_id', $vendor->dsbs_user_id)->with('defer', $defer); ?>
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
          <a href="http://rfpez-apis.presidentialinnovationfellows.org/exclusions?duns=<?php echo e($vendor->duns); ?>" target="_blank">Yes</a>
        </span>
      <?php else: ?>
        <span class="green">No</span>
      <?php endif; ?>
    </div>
  </div>
</div>