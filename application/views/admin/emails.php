<div class="subheader">
  <?php Section::inject('no_page_header', true) ?>
  <h4>Admin Panel</h4>
</div>
<div class="container inner-container">
  <?php echo View::make('admin.partials.subnav')->with('current_page', 'emails'); ?>
  <h5>Vendors</h5>
  <p><?php echo e(implode(', ', $vendor_emails)); ?></p>
  <h5>Officers</h5>
  <p><?php echo e(implode(', ', $officer_emails)); ?></p>
</div>