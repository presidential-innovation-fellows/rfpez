<?php Section::inject('page_title', $bid->project->title) ?>
<?php Section::inject('page_action', 'Show Bid') ?>
<div class="subheader">
  <div class="container">
    <a href="<?php echo e(route('project', array($project->id))); ?>">&larr; Back to project page</a>
  </div>
</div>
<div class="container inner-container">
  <div class="row">
    <div class="span6">
      <?php echo View::make('projects.partials.full_sow')->with('project', $project); ?>
    </div>
    <div id="column-my-bid" class="span5 offset1">
      <h4>My Bid
</h4>
      <?php echo View::make('bids.partials.bid_details_vendors_view')->with('bid', $bid); ?>
    </div>
  </div>
</div>