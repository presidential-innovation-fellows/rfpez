<ul class="nav nav-list sow-sidebar">
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('background') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('sow_background', array($project->id))); ?>">
      Background
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <?php foreach ($project->sow->template_section_types() as $section_type): ?>
    <?php $active = Helper::active_sidebar("section-" . $section_type) ?>
    <li class="<?php echo Jade\Dumper::_text($active ? 'active':''); ?>">
      <a href="<?php echo Jade\Dumper::_text(route('sow_section', array($project->id, $section_type))); ?>">
        <?php echo Jade\Dumper::_text($section_type); ?>
        <i class="icon-chevron-right"></i>
      </a>
    </li>
  <?php endforeach; ?>
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('fillinblanks') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('sow_fillinblanks', array($project->id))); ?>">
      Fill in Variables
      <i class="icon-chevron-right"></i>
    </a>
  </li>
  <li class="<?php echo Jade\Dumper::_text(Helper::active_sidebar('editdoc') ? 'active':''); ?>">
    <a href="<?php echo Jade\Dumper::_text(route('sow_editdoc', array($project->id))); ?>">
      Edit Document
      <i class="icon-chevron-right"></i>
    </a>
  </li>
</ul>