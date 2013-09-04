<div class="subheader"></div>
<?php Section::inject('page_title', $bid->vendor->company_name) ?>
<?php Section::inject('page_action', 'Small Business Program Representations') ?>
<?php Section::inject('no_page_header', true) ?>
<div class="container inner-container">
  <h4><?php echo e($bid->vendor->company_name); ?> - Small Business Program Representations</h4>
  <pre><?php echo View::make('bids.partials.sba_body')->with('bid', $bid); ?></pre>
</div>