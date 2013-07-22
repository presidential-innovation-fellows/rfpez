<?php Section::inject('page_title', 'My Projects') ?>
<?php Section::start('inside_header'); { ?>
  <a class="officer-only toggle-my-all-projects" href="<?php echo e(route('projects')); ?>">everybody's projects</a>
  <a class="btn btn-small btn-success new-project-btn pull-right" href="<?php echo e( route('new_projects') ); ?>">
    <i class="icon-plus-sign icon-white"></i>
    new project
  </a>
  <!-- <div class="clearfix">&nbsp;</div> -->
<?php } ?>
<?php Section::stop(); ?>
<div class="subheader"></div>
<div class="container inner-container">
  <?php if ($projects): ?>
    <table class="table my-project-table">
      <thead>
        <tr>
          <th class="type"></th>
          <th class="project-title">Project</th>
          <th class="status">Status</th>
          <th class="due">
            Bids Due
            <?php echo Helper::helper_tooltip("Bids are due at 11:59pm EST on the date listed.", "top", false, true); ?>
          </th>
          <th class="actions">Actions</th>
        </tr>
      </thead>
      <tbody class="project">
        <?php foreach($projects as $project): ?>
          <tr class="project-meta <?php // if ($project->source() == Project::SOURCE_NATIVE) echo 'project-meta-highlight'; ?>">
            <td>
              <?php // if ($project->source() == Project::SOURCE_NATIVE): ?>
                <img class="my-project-icon" src="<?php echo e($project->project_type->image()); ?>" title="<?php echo e($project->project_type->name); ?>" alt="<?php echo e($project->project_type->name); ?>" />
              <?php // else: ?>
                <!-- <span class="fbo-import-icon">FBO</span> -->
              <?php // endif; ?>
            </td>
            <td>
              <?php if ($project->status() != Project::STATUS_WRITING_SOW): ?>
                <a class="project-title" href="<?php echo e( route('review_bids', array($project->id)) ); ?>"><?php echo e($project->title); ?></a>
              <?php else: ?>
                <a class="project-title" href="<?php echo e( route('project', array($project->id)) ); ?>"><?php echo e($project->title); ?></a>
              <?php endif; ?>
            </td>
            <td><?php echo e($project->status_text()); ?></td>
            <td><?php echo e($project->formatted_proposals_due_at()); ?></td>
            <td>
              <a class="btn btn-mini" href="<?php echo e( route('project_admin', array($project->id)) ); ?>">Admin</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p><?php echo __("r.projects.mine.none", array("url" => route('new_projects'))); ?></p>
  <?php endif; ?>
</div>