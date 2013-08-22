<?php Section::inject('page_title', __('r.home.index_signed_out.site_tagline')) ?>
<?php Section::inject('no_page_header', true) ?>
<div class="subheader">
  <h1 class="home-tagline">Quality solutions for efficient government</h1>
  <h2 class="home-subline">RFP-EZ delivers savings for taxpayers and new opportunities for small businesses</h2>
  <div class="home-story-graphic">
    <img src="/img/home_image_set.png" />
  </div>
</div>
<div class="container inner-container">
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
</div>