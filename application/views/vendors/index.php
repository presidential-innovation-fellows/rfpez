<?php Section::inject('page_title', 'Browse Vendors') ?>
<?php Section::inject('current_page', 'vendors-index') ?>
<div class="vendors-wrapper">
  <div class="vendors">
    <?php foreach($vendors as $vendor): ?>
      <div class="media vendor well">
        <div class="company-name pull-left">
          <a href="<?php echo Jade\Dumper::_text(route('vendor', array($vendor->vendor_id))); ?>" data-pjax="data-pjax"><?php echo Jade\Dumper::_text($vendor->company_name); ?></a>
        </div>
        <div class="ballpark-price pull-right"><?php echo Jade\Dumper::_text($vendor->ballpark_price_display()); ?></div>
        <div class="clearfix"></div>
        <div class="vendor-image-preview-frame">
          <a href="<?php echo Jade\Dumper::_text(route('vendor', array($vendor->vendor_id))); ?>" data-pjax="data-pjax">
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
  <div class="finished-loading-text"><?php echo Jade\Dumper::_html(__("r.vendors.index.viewing_all")); ?></div>
</div>