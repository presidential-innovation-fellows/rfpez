<ul class="nav nav-list sow-sidebar">
  <li class="<?php echo e(Helper::active_sidebar('background') ? 'active':''); ?>">
    <a href="<?php echo e(route('project_background', array($project->id))); ?>">
      1. Background
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo e(Helper::active_sidebar('sections') ? 'active':''); ?>">
    <a href="<?php echo e(route('project_sections', array($project->id))); ?>">
      2. Customize Sections
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo e(Helper::active_sidebar('fill_in_blanks') ? 'active':''); ?>">
    <a href="<?php echo e(route('project_blanks', array($project->id))); ?>">
      3. Fill in Blanks
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo e(Helper::active_sidebar('timeline') ? 'active':''); ?>">
    <a href="<?php echo e(route('project_timeline', array($project->id))); ?>">
      4. <?php echo e($project->price_type == Project::PRICE_TYPE_HOURLY ? 'Hourly Prices' : 'Timeline'); ?>
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo e(Helper::active_sidebar('review') ? 'active':''); ?>">
    <a href="<?php echo e(route('project_review', array($project->id))); ?>">
      5. Review
      <i class="icon-chevron-right"></i>
    </a>
  </li>
</ul>