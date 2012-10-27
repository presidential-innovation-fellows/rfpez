<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Background") ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<h4>Background</h4>
<form class="background-form" method="POST">
  <textarea name="project[background]"><?php echo Jade\Dumper::_html($project->background); ?></textarea>
  <div class="form-actions">
    <button class="btn btn-primary">Continue &rarr;</button>
  </div>
</form>