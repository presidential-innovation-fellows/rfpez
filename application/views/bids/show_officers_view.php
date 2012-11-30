<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Bid from ".$bid->vendor->company_name) ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<?php echo View::make('bids.partials.dismiss_modal'); ?>
<a href="<?php echo e(route('review_bids', array($project->id))); ?>">&larr; Back to bid review list</a>
<h1>Bid from <?php echo e($bid->vendor->company_name); ?>
</h1>
<?php echo View::make('bids.partials.bid_details_officers_view')->with('bid', $bid)->with('defer', false); ?>