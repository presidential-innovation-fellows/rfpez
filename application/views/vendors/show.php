<?php Section::inject('page_title', $vendor->company_name) ?>
<?php Section::inject('no_page_header', true) ?>
<div class="vendor-detail">
  <div class="company-name pull-left"><?php echo Jade\Dumper::_text($vendor->company_name); ?></div>
  <div class="homepage-url btn pull-right">
    <a href="<?php echo Jade\Dumper::_text($vendor->homepage_url); ?>">view company website</a>
  </div>
  <div class="clearfix"></div>
  <div class="ballpark-price"><?php echo Jade\Dumper::_text($vendor->ballpark_price_display()); ?></div>
  <hr class="dark" />
  <div class="more-info">
    <p><?php echo Jade\Dumper::_text($vendor->more_info); ?></p>
  </div>
  <div class="vendor-image-preview-frame pull-left">
    <img src="<?php echo Jade\Dumper::_text($vendor->image_url); ?>" />
  </div>
  <div class="data pull-left span5 offset1">
    <?php echo Jade\Dumper::_html(datum("Contact Name", $vendor->contact_name)); ?>
    <?php echo Jade\Dumper::_html(datum("Email", $vendor->user->email, true)); ?>
    <?php echo Jade\Dumper::_html(datum("Address", $vendor->address."<br />".$vendor->city.", ".$vendor->state." ".$vendor->zip)); ?>
    <?php echo Jade\Dumper::_html(datum("Website", $vendor->homepage_url, true)); ?>
    <?php echo Jade\Dumper::_html(datum("Portfolio", $vendor->portfolio_url, true)); ?>
    <?php echo Jade\Dumper::_html(datum("Source code", $vendor->sourcecode_url, true)); ?>
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
          <?php echo Jade\Dumper::_html(View::make('vendors.partials.dsbs_certifications')->with('user_id', $vendor->dsbs_user_id)->with('defer', false)); ?>
        <?php else: ?>
          <span class="red">No</span>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
</div>