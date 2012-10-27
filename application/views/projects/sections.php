<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Sections") ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span8">
    <h4>Available Sections for <?php echo Jade\Dumper::_text($project->project_type->name); ?></h4>
  </div>
  <div class="span4">
    <h4>Selected Sections</h4>
  </div>
</div>
<div class="row-fluid">
  <div class="span8 available-sections-wrapper">
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
              <a class="btn btn-mini add-button" data-href="<?php echo Jade\Dumper::_text(route('project_section_add', array($project->id, $section->id))); ?>">Use This &rarr;</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="span4">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.selected_sections')->with('project', $project)); ?>
  </div>
</div>
<div class="form-actions">
  <a class="btn btn-primary">Continue</a>
</div>