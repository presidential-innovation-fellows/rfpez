<?php Section::inject('page_title', 'Browse Vendors') ?>
<?php Section::inject('current_page', 'vendors-index') ?>
<div class="vendors-wrapper">
  <div class="vendors">
    <?php foreach($vendors as $vendor): ?>
      <div class="media vendor well">
        <div class="company-name pull-left">
          <a href="<?php echo Jade\Dumper::_text(route('vendor', array($vendor->id))); ?>"><?php echo Jade\Dumper::_text($vendor->company_name); ?></a>
        </div>
        <div class="ballpark-price pull-right"><?php echo Jade\Dumper::_text($vendor->ballpark_price_display()); ?></div>
        <div class="clearfix"></div>
        <div class="vendor-image-preview-frame">
          <a href="<?php echo Jade\Dumper::_text(route('vendor', array($vendor->id))); ?>">
            <img src="<?php echo Jade\Dumper::_text($vendor->image_url); ?>" />
          </a>
        </div>
        <div class="more-info"><?php echo Jade\Dumper::_text($vendor->more_info); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="loading-spinner">
    <?= HTML::image('img/spinner.gif') ?>
  </div>
  <div class="finished-loading-text">You're currently viewing all vendors.</div>
</div>