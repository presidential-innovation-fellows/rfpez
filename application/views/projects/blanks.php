<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Fill in Blanks") ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<h4>Fill in the Blank</h4>
<form method="POST">
  <div class="fill-in-blanks">
    <?php echo Jade\Dumper::_html(SowVariableParser::parse(View::make('projects.partials.background_and_sections')->with('project', $project), $project->variables)); ?>
  </div>
  <div class="form-actions">
    <button class="btn btn-primary">Next &rarr;</button>
  </div>
</form>