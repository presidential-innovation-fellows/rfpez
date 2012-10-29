<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Edit Sections") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'edit_sections') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.edit_section_modal')->with('project', $project)); ?>
<button class="btn btn-success pull-right add-section-button">
  Add Section
  <i class="icon-white icon-plus-sign"></i>
</button>
<div class="row-fluid">
  <div class="span3">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.sow_composer_sidebar')->with('project', $project)); ?>
  </div>
  <div class="span9">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.sections_for_editing')->with('project', $project)); ?>
    <div class="form-actions">
      <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_blanks', array($project->id))); ?>">Fill in Blanks &rarr;</a>
    </div>
  </div>
</div>