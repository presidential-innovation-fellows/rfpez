<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Background") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'background') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.sow_composer_sidebar')->with('project', $project)); ?>
  </div>
  <div class="span9">
    <div class="alert alert-info">
      First, let's compose a background and scope for your SOW. Tell us about your organization,
      and the problem you're trying to solve with this SOW.
    </div>
    <h5 class="sidebar-section-title">Writing A Great SOW</h5>
    <ul>
      <li>The background should identify the work in very general terms</li>
      <li>Describe your organization and why you're pursuing these goals</li>
      <li>Now is the time to mention any regulations or laws affecting the job.</li>
      <li>2-5 Paragraphs in total</li>
      <li>Write so your neighbor can understand what you write.</li>
    </ul>
    <form class="background-form" method="POST">
      <div class="wysiwyg-wrapper">
        <textarea class="wysihtml5" name="project[background]"><?php echo Jade\Dumper::_html($project->background); ?></textarea>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary">Select Sections &rarr;</button>
      </div>
    </form>
  </div>
</div>