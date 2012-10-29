<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('page_action', "Post on FBO") ?>
<?php Section::inject('active_subnav', 'post_on_fbo') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<h5>Step 1</h5>
<p class="readable-width">
  Create a new notice on FBO like you normally do. Be sure to use a unique solicitation number,
  which you will need in the next step. When it comes time to enter the body, just
  copy and paste the text below exactly as-is.
</p>
<input class="input-xxlarge" type="text" value="<?php echo Jade\Dumper::_text(View::make('projects.partials.fbo_body')->with('project', $project)); ?>" data-select-text-on-focus="true" />
<h5>Step 2</h5>
<p>Enter the solicitation number that you used on FBO.</p>
<form id="sync-with-fbo-form" method="POST">
  <div class="control-group">
    <div class="input-append">
      <input type="text" name="fbo_solnbr" placeholder="Solitication Number" />
      <button class="btn btn-primary" data-loading-text="Syncing with FBO...">Sync with FBO</button>
    </div>
  </div>
</form>