<div class="subheader"><!-- subheader for review bids -->
  <?php Section::inject('page_title', $project->title) ?>
  <?php Section::inject('page_action', "Review Bids") ?>
  <?php Section::inject('active_subnav', 'review_bids') ?>
  <?php Section::inject('no_page_header', true) ?>
  <?php Section::inject('current_page', 'bid-review') ?>
  <?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
  <?php echo View::make('bids.partials.dismiss_modal'); ?>
  <?php echo View::make('bids.partials.award_modal')->with('project', $project); ?>
</div>
<div class="container inner-container">
  <div class="well">
    <a id="review-tips-toggle" data-hide-text="Hide Tips [-]">Show Tips [+]</a>
    <div id="review-tips" class="collapse">
      <ul>
        <li><?php echo e(__("r.bids.review.stars_tip")); ?></li>
      </ul>
    </div>
  </div>
  <div class="winning-bid-table-wrapper <?php echo e($project->winning_bid() ? '' : 'hide'); ?>">
    <h5>Winning Bid</h5>
    <table class="table bids-table winning-bid">
      <thead>
        <tr>
          <th class="unread-and-star" colspan="2"></th>
          <th class="vendor-name">Vendor Name</th>
          <th class="price">Price</th>
          <th class="actions">Actions</th>
        </tr>
      </thead>
      <?php if ($project->winning_bid()): ?>
        <?php echo View::make('bids.partials.bid_for_review')->with('bid', $project->winning_bid()); ?>
      <?php endif; ?>
    </table>
  </div>
  <h5 class="content-table-title">Bids awaiting review</h5>
  <table class="table bids-table open-bids">
    <thead>
      <tr>
        <th class="unread-and-star" colspan="2"></th>
        <th class="vendor-name">Vendor Name</th>
        <th class="price">Price</th>
        <th class="actions">Actions</th>
      </tr>
    </thead>
    <?php foreach($open_bids as $bid): ?>
      <?php echo View::make('bids.partials.bid_for_review')->with('bid', $bid); ?>
    <?php endforeach; ?>
  </table>
  <h5>Declined bids</h5>
  <table class="table bids-table dismissed-bids">
    <thead>
      <tr>
        <th class="unread-and-star" colspan="2"></th>
        <th class="vendor-name">Vendor Name</th>
        <th class="price">Price</th>
        <th class="actions">Actions</th>
      </tr>
    </thead>
    <?php foreach($dismissed_bids as $bid): ?>
      <?php echo View::make('bids.partials.bid_for_review')->with('bid', $bid); ?>
    <?php endforeach; ?>
  </table>
</div>