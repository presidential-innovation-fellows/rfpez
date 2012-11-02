<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Bid from ".$bid->vendor->company_name) ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<?php echo Jade\Dumper::_html(View::make('bids.partials.dismiss_modal')); ?>
<a href="<?php echo Jade\Dumper::_text(route('review_bids', array($project->id))); ?>" data-pjax="data-pjax">&larr; Back to bid review list</a>
<h1>Bid from <?php echo Jade\Dumper::_text($bid->vendor->company_name); ?>
</h1>
<?php echo Jade\Dumper::_html(View::make('bids.partials.bid_details_officers_view')->with('bid', $bid)->with('defer', false)); ?>