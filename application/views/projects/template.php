<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Select a Template") ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<h4>Select a Template</h4>
<div class="alert alert-info">
  Here are some statements of work from real, successful procurements for the same type of project you're doing.
  When you "fork" one of these templates, we'll grab all the good bits and then let you customize it to suit the
  details of your specific project.
</div>
<ul class="templates-list">
  <?php foreach($templates as $template): ?>
    <li class="template">
      <div class="row-fluid">
        <div class="span6">
          <div class="title"><?php echo Jade\Dumper::_text($template->title); ?></div>
          <div class="author"><?php echo Jade\Dumper::_text($template->owner()->name); ?></div>
        </div>
        <div class="span4">
          <div class="forked">
            Forked <a href="#"><?php echo Jade\Dumper::_text($template->fork_count); ?> <?php echo Jade\Dumper::_text(Str::plural('time', $template->fork_count)); ?></a>
          </div>
          <?php if ($template->recommended): ?>
            <div class="recommended">&star; Recommended Template</div>
          <?php endif; ?>
        </div>
        <div class="span2">
          <a class="btn" href="<?php echo Jade\Dumper::_text(route('project_template_post', array($project->id, $template->id))); ?>">Fork</a>
        </div>
      </div>
    </li>
  <?php endforeach; ?>
  <?php if ($more_templates_count): ?>
    <li class="show-more">
      <a href="#">See <?php echo Jade\Dumper::_text($more_templates_count); ?> more <?php echo Jade\Dumper::_text(Str::plural('template', $more_templates_count)); ?></a>
    </li>
  <?php endif; ?>
</ul>
<h4>...Or Start From Scratch</h4>
<div class="alert alert-info">
  Prefer to roll your own SOW? That's great too! You'll still have access to our library of pre-written sections.
</div>
<a class="btn" href="<?php echo Jade\Dumper::_text(route('project_background', array($project->id))); ?>">Start From Scratch</a>