<?php Section::inject('page_title', (Auth::officer() ? 'Everybody\'s Projects' : 'Projects' )) ?>
<?php Section::start('inside_header'); { ?>
  <a class="officer-only toggle-my-all-projects" href="<?php echo Jade\Dumper::_text(route('my_projects')); ?>" data-pjax="data-pjax">my projects only</a>
  <div class="search-projects pull-right">
    <input id="filter-projects-input" class="search-query" type="search" placeholder="Filter projects..." />
  </div>
<?php } ?>
<?php Section::stop(); ?>
<table class="table projects-table">
  <thead>
    <tr>
      <th class="project-title">Project Title</th>
      <th class="type">Type</th>
      <th class="agency">Agency</th>
    </tr>
  </thead>
  <?php foreach($projects as $project): ?>
    <tbody class="project">
      <tr>
        <td>
          <a href="<?php echo Jade\Dumper::_text(route('project', array($project->id))); ?>" data-pjax="data-pjax"><?php echo Jade\Dumper::_text($project->title); ?></a>
          <?php if ($project->is_mine()): ?>
            <span class="admin-star">
              <i class="icon-star"></i>
              <a href="<?php echo Jade\Dumper::_text(route('review_bids', array($project->id))); ?>" data-pjax="data-pjax">Review Bids</a>
            </span>
          <?php endif; ?>
          <?php if ($bid = $project->my_bid()): ?>
            <span class="admin-star">
              <i class="icon-star"></i>
              <?php if ($bid->submitted_at): ?>
                <a href="<?php echo Jade\Dumper::_text(route('bid', array($project->id, $project->my_bid()->id))); ?>" data-pjax="data-pjax">View my bid</a>
              <?php else: ?>
                <a href="<?php echo Jade\Dumper::_text(route('new_bids', array($project->id))); ?>" data-pjax="data-pjax">Continue Writing Bid</a>
              <?php endif; ?>
            </span>
          <?php endif; ?>
        </td>
        <td><?php echo Jade\Dumper::_text(@Project::$naics_codes[$project->naics_code] ?: $project->naics_code); ?></td>
        <td><?php echo Jade\Dumper::_text($project->agency); ?></td>
      </tr>
      <tr class="project-details">
        <td colspan="3"><?php echo Jade\Dumper::_text($project->sow->background_and_scope()); ?></td>
      </tr>
      <tr class="deliverables">
        <td colspan="3">
          <strong>Deliverables:
</strong>
          <?php echo Jade\Dumper::_text($project->parsed_deliverables_list()); ?>
        </td>
      </tr>
    </tbody>
  <?php endforeach; ?>
</table>