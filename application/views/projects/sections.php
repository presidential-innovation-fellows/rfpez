<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Sections") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'select_sections') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.sow_composer_sidebar')->with('project', $project)); ?>
  </div>
  <div class="span9">
    <h5>Selected Sections
</h5>
    <?php echo Jade\Dumper::_html(View::make('projects.partials.selected_sections')->with('project', $project)); ?>
    <h5>Available Sections</h5>
    <span>
      <em>Don't see what you want? In the next step you can create new sections.</em>
    </span>
    <div>
      <input id="available-sections-filter" class="search-query pull-right" type="text" placeholder="Filter" />
    </div>
    <table class="table table-striped available-sections-table" data-project-id="<?php echo Jade\Dumper::_text($project->id); ?>">
      <thead>
        <tr>
          <th width="50%">Title</th>
          <th width="20%">Category</th>
          <th width="15%">Times Used</th>
          <th width="15%">Use</th>
        </tr>
      </thead>
      <tbody class="loading-placeholder">
        <tr>
          <td colspan="4">Loading...</td>
        </tr>
      </tbody>
      <?php echo Jade\Dumper::_html(View::make('projects.partials.available_sections_tbody')->with('project', $project)->with('available_sections', $available_sections)); ?>
    </table>
    <div class="form-actions">
      <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_sections_edit', array($project->id))); ?>">Edit Sections &rarr;</a>
    </div>
  </div>
</div>