<?php Section::inject('page_title', 'My Projects') ?>
<?php Section::start('inside_header'); { ?>
  <a class="officer-only toggle-my-all-projects" href="<?php echo Jade\Dumper::_text(route('projects')); ?>" data-pjax="data-pjax">everybody's projects</a>
  <a class="btn btn-small btn-success new-project-btn pull-right" href="<?php echo Jade\Dumper::_text( route('new_projects') ); ?>" data-pjax="data-pjax">
    <i class="icon-plus-sign icon-white"></i>
    new project
  </a>
  <div class="clearfix">&nbsp;</div>
<?php } ?>
<?php Section::stop(); ?>
<?php if ($projects): ?>
  <table class="table my-project-table">
    <thead>
      <tr>
        <th class="type"></th>
        <th class="project-title">Project</th>
        <th class="status">Status</th>
        <th class="due">Bids Due</th>
        <th class="actions">Actions</th>
      </tr>
    </thead>
    <tbody class="project">
      <?php foreach($projects as $project): ?>
        <tr class="project-meta">
          <td>
            <img class="my-project-icon" src="<?php echo Jade\Dumper::_text($project->project_type->image()); ?>" title="<?php echo Jade\Dumper::_text($project->project_type->name); ?>" alt="<?php echo Jade\Dumper::_text($project->project_type->name); ?>" />
          </td>
          <td>
            <?php if ($project->status() != Project::STATUS_WRITING_SOW): ?>
              <a class="project-title" href="<?php echo Jade\Dumper::_text( route('review_bids', array($project->id)) ); ?>" data-pjax="data-pjax"><?php echo Jade\Dumper::_text($project->title); ?></a>
            <?php else: ?>
              <a class="project-title" href="<?php echo Jade\Dumper::_text( route('project', array($project->id)) ); ?>" data-pjax="data-pjax"><?php echo Jade\Dumper::_text($project->title); ?></a>
            <?php endif; ?>
          </td>
          <td><?php echo Jade\Dumper::_text($project->status_text()); ?></td>
          <td><?php echo Jade\Dumper::_text($project->formatted_proposals_due_at()); ?></td>
          <td>
            <a class="btn btn-mini" href="<?php echo Jade\Dumper::_text( route('project_admin', array($project->id)) ); ?>" data-pjax="data-pjax">Admin</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>
    You're not currently collaborating on any projects. You can
    <a href="<?php echo Jade\Dumper::_text(route('new_projects')); ?>" data-pjax="true">create a new project</a>
    or ask someone to add you as a collaborator on a project that already exists.
  </p>
<?php endif; ?>