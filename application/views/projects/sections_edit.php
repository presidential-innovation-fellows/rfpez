<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Edit Sections") ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.edit_section_modal')->with('project', $project)); ?>
<h4>
  Edit Sections
  <button class="btn btn-success pull-right add-section-button">
    Add Section
    <i class="icon-white icon-plus-sign"></i>
  </button>
</h4>
<hr />
<?php echo Jade\Dumper::_html(View::make('projects.partials.sections_for_editing')->with('project', $project)); ?>
<div class="form-actions">
  <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_blanks', array($project->id))); ?>">Next &rarr;</a>
</div>