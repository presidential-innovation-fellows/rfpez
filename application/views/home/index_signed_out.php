<?php Section::inject('page_title', 'A Technology Marketplace That Everybody Loves') ?>
<div class="span5">
  <h3>For Small Business</h3>
  <p class="main-description">
    Create a simple online profile and begin bidding
    on <a href="<?php echo Jade\Dumper::_text( route('projects') ); ?>">projects</a>.
    If you're selected to work on one, we'll walk you through the government registration process.
  </p>
  <a class="btn btn-warning btn-large" href="<?php echo Jade\Dumper::_text( route('new_vendors') ); ?>" data-pjax="data-pjax">Register as a Company</a>
</div>
<div class="span5">
  <h3>For Government</h3>
  <p class="main-description">
    Make great statements of work and find great contractors.
    Browse eligible firms and see their online portfolios.
    Receive bids from small innovative tech companies.
  </p>
  <a class="btn btn-warning btn-large" href="<?php echo Jade\Dumper::_text( route('new_officers') ); ?>" data-pjax="data-pjax">Register as a Contracting Officer</a>
</div>