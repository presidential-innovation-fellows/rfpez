<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Review") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'review') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo View::make('projects.partials.sow_composer_sidebar')->with('project', $project); ?>
  </div>
  <div class="span9">
    <div class="well"><?php echo __("r.projects.review.text", array("post_url" => route('project_post_on_fbo', array($project->id)), "invite_url" =>route('project_admin', array($project->id)))); ?>
</div>
    <?php echo View::make('projects.partials.full_sow')->with('project', $project); ?>
    <div class="form-actions">
      <a class="btn btn-primary" href="<?php echo e(route('project_post_on_fbo', array($project->id))); ?>">Looks Good! &rarr;</a>
    </div>
  </div>
</div>