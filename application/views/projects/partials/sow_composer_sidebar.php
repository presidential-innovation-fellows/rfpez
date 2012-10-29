<ul class="nav nav-list sow-sidebar">
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('background') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('project_background', array($project->id))); ?>">
      Background
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('select_sections') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('project_sections', array($project->id))); ?>">
      Select Sections
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('edit_sections') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('project_sections_edit', array($project->id))); ?>">
      Edit Sections
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('fill_in_blanks') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('project_blanks', array($project->id))); ?>">
      Fill in Blanks
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('timeline') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('project_timeline', array($project->id))); ?>">
      Timeline
      <i class="icon-chevron-right"></i>
    </a>
  </li>
</ul>