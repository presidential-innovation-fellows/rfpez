<?php Section::inject('page_title', 'A Technology Marketplace That Everybody Loves') ?>
<?php Section::inject('no_page_header', true) ?>
<div class="hero-unit">
  <h1>
    EasyBid
    <small><?php echo Jade\Dumper::_text(__('r.home.index_signed_out.site_tagline')); ?></small>
  </h1>
</div>
<div class="row-fluid">
  <div class="span6">
    <h5><?php echo Jade\Dumper::_html(__('r.home.index_signed_out.biz_header')); ?></h5>
    <p class="main-description"><?php echo Jade\Dumper::_html(__('r.home.index_signed_out.biz_description', array('url' => route('projects')))); ?></p>
    <a class="btn btn-warning btn-large" href="<?php echo Jade\Dumper::_text( route('new_vendors') ); ?>" data-pjax="data-pjax"><?php echo Jade\Dumper::_html(__('r.home.index_signed_out.biz_button')); ?></a>
  </div>
  <div class="span6">
    <h5><?php echo Jade\Dumper::_html(__('r.home.index_signed_out.gov_header')); ?></h5>
    <p class="main-description"><?php echo Jade\Dumper::_html(__('r.home.index_signed_out.gov_description')); ?></p>
    <a class="btn btn-warning btn-large" href="<?php echo Jade\Dumper::_text( route('new_officers') ); ?>" data-pjax="data-pjax"><?php echo Jade\Dumper::_html(__('r.home.index_signed_out.gov_button')); ?></a>
  </div>
</div>