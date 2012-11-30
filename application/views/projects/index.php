<?php Section::inject('page_title', (Auth::officer() ? 'Everybody\'s Projects' : 'Projects' )) ?>
<?php if (count($projects) > 0): ?>
  <?php Section::start('inside_header'); { ?>
    <small>(<a href="<?php echo e(route('project_rss', 'rss')); ?>">rss</a> / <a href="<?php echo e(route('project_rss', 'atom')); ?>">atom</a>)</small>
    <a class="officer-only toggle-my-all-projects" href="<?php echo e(route('my_projects')); ?>">my projects only</a>
    <div class="search-projects pull-right">
      <input id="filter-projects-input" class="search-query" type="search" placeholder="Filter projects..." />
    </div>
  <?php } ?>
  <?php Section::stop(); ?>
  <table class="table projects-table">
    <thead>
      <tr>
        <th class="type hidden-phone">Type</th>
        <th class="project-title">Project Title</th>
        <th class="agency visible-desktop">Agency</th>
        <th class="due">
          Bids Due
          <?php echo Helper::helper_tooltip("Bids are due at 11:59pm EST on the date listed.", "top", false, true); ?>
        </th>
      </tr>
    </thead>
    <?php foreach($projects as $project): ?>
      <tbody class="project">
        <tr class="project-meta">
          <td class="hidden-phone">
            <img src="<?php echo e($project->project_type->image()); ?>" title="<?php echo e($project->project_type->name); ?>" alt="<?php echo e($project->project_type->name); ?>" />
          </td>
          <td>
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
                <?php if ($bid->submitted_at): ?>
                  <a href="<?php echo e(route('bid', array($project->id, $project->my_bid()->id))); ?>">View my bid</a>
                <?php else: ?>
                  <a href="<?php echo e(route('new_bids', array($project->id))); ?>">Continue Writing Bid</a>
                <?php endif; ?>
              </span>
            <?php endif; ?>
            <p class="project-description-truncated"><?php echo e(Helper::truncate($project->background, 13)); ?></p>
          </td>
          <td class="visible-desktop"><?php echo e($project->agency); ?></td>
          <td><?php echo e($project->formatted_proposals_due_at()); ?></td>
        </tr>
      </tbody>
    <?php endforeach; ?>
  </table>
<?php else: ?>
  <p><?php echo e(__("r.projects.index.none")); ?></p>
<?php endif; ?>