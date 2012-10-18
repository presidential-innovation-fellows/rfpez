<?php Section::inject('page_title', $bid->project->title) ?>
<?php Section::inject('page_action', 'Show Bid') ?>
<a href="<?php echo Jade\Dumper::_text(route('project', array($project->id))); ?>" data-pjax="data-pjax">&larr; Back to project page</a>
<div class="row">
  <div class="span6">
    <?php echo Jade\Dumper::_html($project->body); ?>
  </div>
  <div class="span5 offset1">
    <h1>My Bid
</h1>
    <?php echo Jade\Dumper::_html(View::make('bids.partials.bid_details_vendors_view')->with('bid', $bid)); ?>
  </div>
</div>