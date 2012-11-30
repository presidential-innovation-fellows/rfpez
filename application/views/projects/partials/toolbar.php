<h4>
  <?php echo e($project->title); ?>
  <span class="pull-right">Status: <?php echo e($project->status_text()); ?></span>
</h4>
<div class="nav nav-tabs project-subnav">
  <?php if ($project->status() == Project::STATUS_WRITING_SOW): ?>
    <li class="<?php echo e(Helper::active_subnav('create') ? 'active':''); ?>">
      <a href="<?php echo e(route('project', array($project->id))); ?>" data-pjax="data-pjax">Write SOW</a>
    </li>
    <li class="<?php echo e(Helper::active_subnav('post_on_fbo') ? 'active':''); ?>">
      <a href="<?php echo e(route('project_post_on_fbo', array($project->id))); ?>" data-pjax="data-pjax">Post on FBO</a>
    </li>
  <?php elseif ($project->status() == Project::STATUS_ACCEPTING_BIDS || $project->status() == Project::STATUS_REVIEWING_BIDS || $project->status() == Project::STATUS_CONTRACT_AWARDED): ?>
    <li class="<?php echo e(Helper::active_subnav('view') ? 'active':''); ?>">
      <a href="<?php echo e(route('project', array($project->id))); ?>" data-pjax="data-pjax">View Posting</a>
    </li>
    <li class="<?php echo e(Helper::active_subnav('review_bids') ? 'active':''); ?>">
      <a href="<?php echo e(route('review_bids', array($project->id))); ?>" data-pjax="data-pjax">Review Bids (<?php echo e($project->submitted_bids()->count()); ?>)</a>
    </li>
  <?php endif; { ?>
    <li class="pull-right <?php echo e(Helper::active_subnav('admin') ? 'active':''); ?>">
      <a href="<?php echo e(route('project_admin', array($project->id))); ?>" data-pjax="data-pjax">Admin</a>
    </li>
    <li class="pull-right <?php echo e(Helper::active_subnav('comments') ? 'active':''); ?>">
      <a href="<?php echo e(route('comments', array($project->id))); ?>" data-pjax="data-pjax">Comments (<?php echo e($project->comments()->count()); ?>)</a>
    </li>
  <?php } ?>
</div>