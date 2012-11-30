<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('page_action', "Post on FBO") ?>
<?php Section::inject('active_subnav', 'post_on_fbo') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<div class="row-fluid">
  <div class="span7">
    <h5>Step 1</h5>
    <p><?php echo __("r.projects.post_on_fbo.step1"); ?></p>
    <input class="input-xxlarge" type="text" value="<?php echo e(View::make('projects.partials.fbo_body')->with('project', $project)); ?>" data-select-text-on-focus="true" />
    <h5>Step 2</h5>
    <p><?php echo __("r.projects.post_on_fbo.step2", array("due" => $project->formatted_proposals_due_at(), "url" => route('project_admin', array($project->id)))); ?></p>
    <form id="sync-with-fbo-form" method="POST">
      <div class="control-group">
        <button class="btn btn-primary btn-large">Sync with FBO</button>
      </div>
    </form>
  </div>
  <div class="span4 offset1">
    <div class="well">
      <h5>Not a Contracting Officer?</h5>
      <p><?php echo __("r.projects.post_on_fbo.not_co"); ?></p>
    </div>
  </div>
</div>