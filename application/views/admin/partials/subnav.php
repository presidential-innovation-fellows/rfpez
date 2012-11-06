<h4>Admin Panel</h4>
<div class="navbar navbar-inverse">
  <div class="navbar-inner">
    <ul class="nav">
      <li class="<?php echo Jade\Dumper::_text($current_page == 'projects' ? 'active' : ''); ?>">
        <a href="<?php echo Jade\Dumper::_text(route('admin_projects')); ?>">Projects</a>
      </li>
      <li class="<?php echo Jade\Dumper::_text($current_page == 'officers' ? 'active' : ''); ?>">
        <a href="<?php echo Jade\Dumper::_text(route('admin_officers')); ?>">Officers</a>
      </li>
      <li class="<?php echo Jade\Dumper::_text($current_page == 'vendors' ? 'active' : ''); ?>">
        <a href="<?php echo Jade\Dumper::_text(route('admin_vendors')); ?>">Vendors</a>
      </li>
      <?php if (Auth::officer()->is_role_or_higher(Officer::ROLE_SUPER_ADMIN)): ?>
        <li class="<?php echo Jade\Dumper::_text($current_page == 'project_sections' ? 'active' : ''); ?>">
          <a href="<?php echo Jade\Dumper::_text(route('admin_project_sections')); ?>">Project Sections</a>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</div>