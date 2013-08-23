<h4>
  <?php echo e($project->title); ?>
  <div class="project-status">Status: <?php echo e($project->status_text()); ?></div>
  <?php if (!$project->is_mine()): ?>
    <div class="project-owner">Owner: <?php echo e($project->owner()->name); ?> (<?php echo e($project->owner()->agency); ?>)</div>
  <?php endif; ?>
</h4>
<div class="nav nav-tabs project-subnav">
  <?php if ($project->status() == Project::STATUS_WRITING_SOW): ?>
    <li class="<?php echo e(Helper::active_subnav('create') ? 'active':''); ?>">
      <a href="<?php echo e(route('project', array($project->id))); ?>">Write SOW</a>
    </li>
    <li class="<?php echo e(Helper::active_subnav('post_on_fbo') ? 'active':''); ?>">
      <a href="<?php echo e(route('project_post_on_fbo', array($project->id))); ?>">Post on FBO</a>
    </li>
  <?php elseif ($project->status() == Project::STATUS_ACCEPTING_BIDS || $project->status() == Project::STATUS_REVIEWING_BIDS || $project->status() == Project::STATUS_CONTRACT_AWARDED): ?>
    <li class="<?php echo e((Helper::active_subnav('view') || Helper::active_subnav('')) ? 'active':''); ?>">
      <a href="<?php echo e(route('project', array($project->id))); ?>">View Posting</a>
    </li>
    <?php if ($project->is_mine() && ($project->source() == Project::SOURCE_NATIVE)): ?>
      <li class="<?php echo e(Helper::active_subnav('review_bids') ? 'active':''); ?>">
        <a href="<?php echo e(route('review_bids', array($project->id))); ?>">Review Bids (<?php echo e($project->submitted_bids()->count()); ?>)</a>
      </li>
    <?php endif; ?>
  <?php endif; { ?>
    <li class="pull-right <?php echo e(Helper::active_subnav('admin') ? 'active':''); ?>">
      <a href="<?php echo e(route('project_admin', array($project->id))); ?>">Admin</a>
    </li>
    <li class="pull-right <?php echo e(Helper::active_subnav('comments') ? 'active':''); ?>">
      <a href="<?php echo e(route('comments', array($project->id))); ?>">Comments (<?php echo e($project->comments()->count()); ?>)</a>
    </li>
  <?php } ?>
</div>