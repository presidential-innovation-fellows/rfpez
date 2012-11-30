<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Background") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'background') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo View::make('projects.partials.sow_composer_sidebar')->with('project', $project); ?>
  </div>
  <div class="span9">
    <div class="alert alert-info"><?php echo e(__("r.projects.background.helper")); ?></div>
    <h5><?php echo e(__("r.projects.background.tips_header")); ?></h5>
    <ul>
      <li><?php echo e(__("r.projects.background.tips.0")); ?></li>
      <li><?php echo e(__("r.projects.background.tips.1")); ?></li>
      <li><?php echo e(__("r.projects.background.tips.2")); ?></li>
      <li><?php echo e(__("r.projects.background.tips.3")); ?></li>
      <li><?php echo e(__("r.projects.background.tips.4")); ?></li>
    </ul>
    <form class="background-form" method="POST">
      <div class="wysiwyg-wrapper">
        <textarea class="wysihtml5" name="project[background]"><?php echo $project->background; ?></textarea>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary">Save and Continue &rarr;</button>
      </div>
    </form>
  </div>
</div>