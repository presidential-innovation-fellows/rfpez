<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Select a Template") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<h5>Select a Template</h5>
<p>
  Here are some statements of work from real, successful procurements for the same type of project you're doing.
  When you "fork" one of these templates, we'll grab all the good bits and then let you customize it to suit the
  details of your specific project.
</p>
<ul class="templates-list">
  <?php if ($templates): ?>
    <?php foreach($templates as $template): ?>
      <li class="template well">
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
            <a class="btn btn-success" href="<?php echo Jade\Dumper::_text(route('project_template_post', array($project->id, $template->id))); ?>">Fork</a>
          </div>
        </div>
      </li>
    <?php endforeach; ?>
    <?php if ($more_templates_count): ?>
      <li class="show-more">
        <a href="#">See <?php echo Jade\Dumper::_text($more_templates_count); ?> more <?php echo Jade\Dumper::_text(Str::plural('template', $more_templates_count)); ?></a>
      </li>
    <?php endif; ?>
  <?php else: ?>
    <li class="no-templates well">
      Sorry, there are no templates available for this project type. Click "Start From Scratch" below,
      and your great SOW could be the first template.
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