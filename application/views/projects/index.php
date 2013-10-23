<?php Section::inject('page_title', (Auth::officer() ? 'Everybody\'s Projects' : 'Projects' )) ?>
<?php Section::inject('no_page_header', true) ?>
<div class="subheader">
  <?php if (!Auth::user()): ?>
    <p class="lead well">Ready to start bidding? <a href="<?php echo e(route('new_vendors')); ?>">Sign up</a> in minutes!</p>
  <?php endif; ?>
  <h4>
    <?php echo (Auth::officer() ? 'Everybody\'s Projects' : 'Projects' ); ?>
    <small>(<a href="<?php echo e(route('project_rss', 'rss')); ?>">rss</a> / <a href="<?php echo e(route('project_rss', 'atom')); ?>">atom</a>)</small>
    <a class="officer-only toggle-my-all-projects" href="<?php echo e(route('my_projects')); ?>">my projects only</a>
    <div class="search-projects pull-right">
      <input id="filter-projects-input" class="search-query" type="search" placeholder="Filter projects..." />
    </div>
  </h4>
</div>
<div class="container inner-container">
  <?php if (count($projects) > 0): ?>
    <table id="projects-table" class="table projects-table">
      <thead>
        <tr>
          <th class="type hidden-phone">Type</th>
          <th class="project-title">Project Title</th>
          <th class="agency visible-desktop">Agency</th>
          <th class="due">
            Bids Due
            <?php echo Helper::helper_tooltip("Bids are due at 11:59pm EST on the date listed unless otherwise noted.", "top", false, true); ?>
          </th>
        </tr>
      </thead>
      <?php foreach($projects as $project): ?>
        <tbody class="project">
          <tr class="<?php echo e((($project->source == Project::SOURCE_NATIVE) ? 'project-meta project-meta-highlight' : 'project-meta')); ?>">
            <td class="type hidden-phone">
              <?php if ($project->source() == Project::SOURCE_NATIVE): ?>
                <img src="<?php echo e($project->project_type->image()); ?>" title="<?php echo e($project->project_type->name); ?>" alt="<?php echo e($project->project_type->name); ?>" />
              <?php elseif ($project->source() == Project::SOURCE_FBO): ?>
                <span class="fbo-import-icon">FBO</span>
              <?php elseif ($project->source() == Project::SOURCE_CHALLENGEGOV): ?>
                <span class="fbo-import-icon">Challenge</span>
              <?php elseif ($project->source() == Project::SOURCE_SBIR): ?>
                <span class="fbo-import-icon">SBIR</span>
              <?php else: ?>
                <span class="fbo-import-icon"></span>
              <?php endif; ?>
            </td>
            <td class="project-title">
              <a class="project-title" href="<?php echo e(route('project', array($project->id))); ?>"><?php echo e($project->title); ?></a>
              <?php if ($project->is_mine()): ?>
                <span class="admin-star">
                  <i class="icon-star"></i>
                  <a href="<?php echo e(route('review_bids', array($project->id))); ?>">Review Bids</a>
                </span>
              <?php endif; ?>
              <?php if ($bid = $project->my_bid()): ?>
                <span class="admin-star">
                  <i class="icon-star"></i>
                  <?php if ($bid->is_amended()): ?>
                    <span class="bid-amended">Amended <?php echo e($bid->amended_at()); ?></span>
                  <?php endif; ?>
                  <?php if ($bid->submitted_at): ?>
                    <a href="<?php echo e(route('bid', array($project->id, $project->my_bid()->id))); ?>">View my bid</a>
                  <?php else: ?>
                    <a href="<?php echo e(route('new_bids', array($project->id))); ?>">Continue Writing Bid</a>
                  <?php endif; ?>
                </span>
              <?php endif; ?>
              <p class="project-description-truncated"><?php echo e($project->background_truncated()); ?></p>
            </td>
            <td class="agency visible-desktop"><?php echo e($project->agency); ?></td>
            <td class="due">
              <?php echo e($project->formatted_proposals_due_at_date()); ?>
              <span class="due-time"><?php echo e($project->formatted_proposals_due_at_time()); ?></span>
            </td>
          </tr>
        </tbody>
      <?php endforeach; ?>
    </table>
  <?php else: ?>
    <p><?php echo e(__("r.projects.index.none")); ?></p>
  <?php endif; ?>
</div>