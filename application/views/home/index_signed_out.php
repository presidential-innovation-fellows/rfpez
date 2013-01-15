<?php Section::inject('page_title', __('r.home.index_signed_out.site_tagline')) ?>
<?php Section::inject('no_page_header', true) ?>
<div class="hero-unit">
  <h1>
    RFP-EZ
    <small><?php echo e(__('r.home.index_signed_out.site_tagline')); ?></small>
  </h1>
</div>
<div class="row-fluid">
  <div class="span6">
    <h5><?php echo __('r.home.index_signed_out.biz_header'); ?></h5>
    <p class="main-description"><?php echo __('r.home.index_signed_out.biz_description'); ?></p>
    <a class="btn btn-warning btn-large" href="<?php echo e( route('projects') ); ?>"><?php echo __('r.home.index_signed_out.biz_button'); ?></a>
  </div>
  <div class="span6">
    <h5><?php echo __('r.home.index_signed_out.gov_header'); ?></h5>
    <p class="main-description"><?php echo __('r.home.index_signed_out.gov_description'); ?></p>
    <a class="btn btn-warning btn-large" href="<?php echo e( route('government') ); ?>"><?php echo __('r.home.index_signed_out.gov_button'); ?></a>
  </div>
</div>