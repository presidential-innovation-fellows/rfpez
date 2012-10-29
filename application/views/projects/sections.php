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
    <h4>Selected Sections
</h4>
    <?php echo Jade\Dumper::_html(View::make('projects.partials.selected_sections')->with('project', $project)); ?>
    <h4>Available Sections</h4>
    <span>
      <em>Don't see what you want? In the next step you can create new sections.</em>
    </span>
    <div class="row-fluid">
      <div class="span6 sort-by">
        Sort by:
        <select>
          <option>Popularity</option>
        </select>
      </div>
      <div class="span6 filter">
        <input class="search-query" type="text" placeholder="Filter" />
      </div>
    </div>
    <table class="table table-striped available-sections-table">
      <thead>
        <tr>
          <th>Title</th>
          <th>Category</th>
          <th>Times Used</th>
          <th>Use</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($available_sections as $section): ?>
          <tr class="section" data-section-id="<?php echo Jade\Dumper::_text($section->id); ?>">
            <td><?php echo Jade\Dumper::_text($section->title); ?></td>
            <td><?php echo Jade\Dumper::_text($section->section_category); ?></td>
            <td><?php echo Jade\Dumper::_text($section->times_used); ?></td>
            <td>
              <a class="btn btn-primary btn-mini add-button" data-href="<?php echo Jade\Dumper::_text(route('project_section_add', array($project->id, $section->id))); ?>">Use This &rarr;</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="form-actions">
      <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_sections_edit', array($project->id))); ?>">Edit Sections &rarr;</a>
    </div>
  </div>
</div>