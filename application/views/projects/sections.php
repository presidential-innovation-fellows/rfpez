<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Customize Sections") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'sections') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<?php echo View::make('projects.partials.add_edit_section_modal')->with('project', $project)->with('available_sections', $available_sections); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo View::make('projects.partials.sow_composer_sidebar')->with('project', $project); ?>
  </div>
  <div class="span9">
    <div class="alert alert-info"><?php echo __("r.projects.sections.drag_helper"); ?></div>
    <button class="btn btn-success pull-right add-section-button">
      Add Section
      <i class="icon-white icon-plus-sign"></i>
    </button>
    <div class="clearfix"></div>
    <?php echo View::make('projects.partials.sections_for_editing')->with('project', $project); ?>
    <div class="form-actions">
      <a class="btn btn-primary" href="<?php echo e(route('project_blanks', array($project->id))); ?>">Save and Continue &rarr;</a>
    </div>
  </div>
</div>