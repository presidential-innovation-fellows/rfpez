<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Edit SOW") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<iframe src='http://beta.etherpad.org/p/rfpez-<?php echo e($project->id); ?>?showChat=false&showLineNumbers=false' width="100%" height="500" style="border: 0; outline: 0;"></iframe>
<div class="form-actions">
  <button class="btn btn-primary">Continue &rarr;</button>
</div>