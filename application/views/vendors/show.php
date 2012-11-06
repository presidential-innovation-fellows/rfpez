<?php Section::inject('page_title', $vendor->company_name) ?>
<?php Section::inject('no_page_header', true) ?>
<div class="vendor-detail">
  <div class="company-name pull-left"><?php echo Jade\Dumper::_text($vendor->company_name); ?></div>
  <div class="homepage-url btn pull-right">
    <a href="<?php echo Jade\Dumper::_text($vendor->homepage_url); ?>" target="_blank">view company website</a>
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
  <div class="pull-left span5 offset1">
    <?php echo Jade\Dumper::_html(View::make('vendors.partials.data')->with('vendor', $vendor)); ?>
  </div>
  <div class="clearfix"></div>
</div>