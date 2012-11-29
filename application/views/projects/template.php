<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Select a Template") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<?php if ($templates): ?>
  <h5><?php echo Jade\Dumper::_html(__("r.projects.template.template_header")); ?></h5>
  <p><?php echo Jade\Dumper::_html(__("r.projects.template.template_text")); ?></p>
  <ul class="templates-list">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.template_lis')->with('templates', $templates)->with('project', $project)); ?>
    <?php if ($more_templates_count): ?>
      <li class="show-more">
        <a class="show-more-templates-link" data-href="<?php echo Jade\Dumper::_text(route('project_more_templates', array($project->id))); ?>">See <?php echo Jade\Dumper::_text($more_templates_count); ?> more <?php echo Jade\Dumper::_text(Str::plural('template', $more_templates_count)); ?>...</a>
        <img class="spinner" src="<?php echo Jade\Dumper::_text(asset('img/spinner.gif')); ?>" />
      </li>
    <?php endif; ?>
  </ul>
  <h5><?php echo Jade\Dumper::_html(__("r.projects.template.scratch_header")); ?></h5>
  <p><?php echo Jade\Dumper::_html(__("r.projects.template.scratch_text")); ?></p>
  <div class="centered">
    <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_background', array($project->id))); ?>">Start From Scratch</a>
  </div>
<?php else: ?>
  <p class="well">
    <?php echo Jade\Dumper::_html(__("r.projects.template.no_templates", array("project_type" => $project->project_type->name))); ?>
    <div class="centered">
      <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_background', array($project->id))); ?>">Let's get started! &rarr;</a>
    </div>
  </p>
<?php endif; ?>