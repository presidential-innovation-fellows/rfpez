<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Fill in Blanks") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'fill_in_blanks') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.sow_composer_sidebar')->with('project', $project)); ?>
  </div>
  <div class="span9">
    <form method="POST">
      <div class="fill-in-blanks">
        <?php echo Jade\Dumper::_html(SowVariableParser::parse(View::make('projects.partials.background_and_sections')->with('project', $project), $project->variables)); ?>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary">Timeline &rarr;</button>
      </div>
    </form>
  </div>
</div>