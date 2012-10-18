<?php Section::inject('page_title', 'My Projects') ?>
<?php Section::start('inside_header'); { ?>
  <a class="officer-only toggle-my-all-projects" href="<?php echo Jade\Dumper::_text(route('projects')); ?>" data-pjax="data-pjax">everybody's projects</a>
  <a class="btn btn-small btn-success new-project-btn pull-right" href="<?php echo Jade\Dumper::_text( route('new_projects') ); ?>" data-pjax="data-pjax">
    <i class="icon-plus-sign icon-white"></i>
    new project
  </a>
<?php } ?>
<?php Section::stop(); ?>
<?php if ($projects): ?>
  <table class="table my-project-table">
    <thead>
      <tr>
        <th>Project</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($projects as $project): ?>
        <tr>
          <td>
            <a href="<?php echo Jade\Dumper::_text( route('project', array($project->id)) ); ?>" data-pjax="data-pjax"><?php echo Jade\Dumper::_text($project->title); ?></a>
          </td>
          <td><?php echo Jade\Dumper::_text($project->status_text()); ?></td>
          <td>
            <a class="btn btn-mini" href="<?php echo Jade\Dumper::_text( route('project_admin', array($project->id)) ); ?>" data-pjax="data-pjax">Admin</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No contracts to display.</p>
<?php endif; ?>