<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Select a Template") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<?php if ($templates): ?>
  <h5>Select a Template</h5>
  <p>
    Here are some statements of work from real, successful procurements for the same type of project you're doing.
    When you "fork" one of these templates, we'll grab all the good bits and then let you customize it to suit the
    details of your specific project.
  </p>
  <ul class="templates-list">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.template_lis')->with('templates', $templates)->with('project', $project)); ?>
    <?php if ($more_templates_count): ?>
      <li class="show-more">
        <a class="show-more-templates-link" data-href="<?php echo Jade\Dumper::_text(route('project_more_templates', array($project->id))); ?>">See <?php echo Jade\Dumper::_text($more_templates_count); ?> more <?php echo Jade\Dumper::_text(Str::plural('template', $more_templates_count)); ?>...</a>
        <img class="spinner" src="<?php echo Jade\Dumper::_text(asset('img/spinner.gif')); ?>" />
      </li>
    <?php endif; ?>
  </ul>
  <h5>...Or Start From Scratch</h5>
  <p>
    Prefer to roll your own SOW? That's great too! You'll still have access to our library of pre-written sections.
  </p>
  <div class="centered">
    <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_background', array($project->id))); ?>">Start From Scratch</a>
  </div>
<?php else: ?>
  <p class="well">
    <strong>Welcome to SOW Composer!</strong> In the future, we hope to have a library of SOW templates that
    can help you get a running start. For now, you're the first in our system to use the <em><?php echo Jade\Dumper::_text($project->project_type->name); ?></em>
    project type, so we'll walk you through the SOW creation process from the start.
    <div class="centered">
      <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_background', array($project->id))); ?>">Let's get started! &rarr;</a>
    </div>
  </p>
<?php endif; ?>