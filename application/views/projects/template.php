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
      template
    </li>
  <?php endforeach; ?>
  <?php if ($more_templates_count): ?>
    <li class="show-more">
      <a href="#">See <?php echo Jade\Dumper::_text($more_templates_count); ?> more <?php echo Jade\Dumper::_text(Str::plural('template', $more_templates_count)); ?></a>
    </li>
  <?php endif; ?>
</ul>
<h4>...Or Start From Scratch</h4>