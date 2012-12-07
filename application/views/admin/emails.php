<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('admin.partials.subnav')->with('current_page', 'emails'); ?>
<h5>Vendors</h5>
<p><?php echo e(implode(', ', $vendor_emails)); ?></p>
<h5>Officers</h5>
<p><?php echo e(implode(', ', $officer_emails)); ?></p>