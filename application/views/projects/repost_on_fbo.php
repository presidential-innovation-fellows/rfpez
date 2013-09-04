<div class="subheader">
  <?php Section::inject('page_title', "$project->title") ?>
  <?php Section::inject('page_action', "Repost on FBO") ?>
  <?php Section::inject('no_page_header', true) ?>
  <?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
</div>
<div class="container inner-container">
  <div class="row-fluid">
    <div class="span7">
      <h5>If you did NOT make any amendments, click here:</h5>
      <form id="amendment-no-changes-form" action="<?php echo e(route('project_amendment_no_changes', array($project->id))); ?>" method="POST">
        <div class="control-group">
          <button class="btn btn-primary btn-large">No Changes Were Made</button>
        </div>
      </form>
      <p>&nbsp;</p>
      <h5>If you DID amend the project:</h5>
      <form id="complete-amendment-form" method="POST">
        <p><?php echo __("r.projects.repost_on_fbo.step1"); ?></p>
        <input type="hidden" name="amendment_description[section_category]" value="Amendments" />
        <input type="hidden" name="amendment_description[title]" value="Amended <?php echo date('m/d/Y'); ?>" />
        <textarea class="input-xxlarge" name="amendment_description[body]"></textarea>
        <p><?php echo __("r.projects.repost_on_fbo.step2"); ?></p>
        <p><?php echo __("r.projects.repost_on_fbo.step3"); ?></p>
        <div class="control-group">
          <button class="btn btn-success btn-large">Sync Amendment with FBO</button>
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
</div>