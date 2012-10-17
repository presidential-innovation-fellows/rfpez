<h4>
  <?php echo Jade\Dumper::_text($project->title); ?>
  <span class="pull-right">Status: <?php echo Jade\Dumper::_text($project->status_text()); ?></span>
</h4>
<div class="nav nav-tabs project-subnav">
  <?php if ($project->status() == Project::STATUS_WRITING_SOW): ?>
    <li class="<?php echo Jade\Dumper::_text(Helper::active_subnav('view') ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('sow_background', array($project->id))); ?>">Create</a>
    </li>
    <li class="<?php echo Jade\Dumper::_text(Helper::active_subnav('review') ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('sow_review', array($project->id))); ?>">Review</a>
    </li>
    <li class="<?php echo Jade\Dumper::_text(Helper::active_subnav('post_on_fbo') ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('project_post_on_fbo', array($project->id))); ?>">Post on FBO</a>
    </li>
  <?php elseif ($project->status() == Project::STATUS_ACCEPTING_BIDS || $project->status() == Project::STATUS_REVIEWING_BIDS || $project->status() == Project::STATUS_CONTRACT_AWARDED): ?>
    <li class="<?php echo Jade\Dumper::_text(Helper::active_subnav('view') ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('project', array($project->id))); ?>">View Posting</a>
    </li>
    <li class="<?php echo Jade\Dumper::_text(Helper::active_subnav('review_bids') ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('review_bids', array($project->id))); ?>">Review Bids (<?php echo Jade\Dumper::_text($project->submitted_bids()->count()); ?>)</a>
    </li>
  <?php endif; { ?>
    <li class="pull-right <?php echo Jade\Dumper::_text(Helper::active_subnav('admin') ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('project_admin', array($project->id))); ?>">Admin</a>
    </li>
    <li class="pull-right <?php echo Jade\Dumper::_text(Helper::active_subnav('comments') ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('comments', array($project->id))); ?>">Comments (<?php echo Jade\Dumper::_text($project->comments()->count()); ?>)</a>
    </li>
  <?php } ?>
</div>