<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('page_action', "Post on FBO") ?>
<?php Section::inject('active_subnav', 'post_on_fbo') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span8">
    <h5>Step 1</h5>
    <p class="readable-width">
      Create a new notice on FBO like you normally do. Be sure to use a unique solicitation number,
      which you will need in the next step. When it comes time to enter the body, just
      copy and paste the text below exactly as-is.
    </p>
    <input class="input-xxlarge" type="text" value="<?php echo Jade\Dumper::_text(View::make('projects.partials.fbo_body')->with('project', $project)); ?>" data-select-text-on-focus="true" />
    <h5>Step 2</h5>
    <p>
      Once you've posted your notice on FBO, click the button below and your project will be open for bids on RFP-EZ.
      The due date you've specified for responses is <strong><?php echo Jade\Dumper::_text($project->formatted_proposals_due_at()); ?></strong>. If you'd
      like to change this, you can do so on the <a href="<?php echo Jade\Dumper::_text(route('project_admin', array($project->id))); ?>">admin page</a>.
    </p>
    <form id="sync-with-fbo-form" method="POST">
      <div class="control-group">
        <button class="btn btn-primary btn-large">Sync with FBO</button>
      </div>
    </form>
  </div>
  <div class="span4">
    <div class="well">
      <h5>Not a Contracting Officer?</h5>
      <p>
        This step is for certified contracting officers only. If that's not you,
        just click on the "admin" tab above and you can invite your CO to
        collaborate with you.
      </p>
    </div>
  </div>
</div>