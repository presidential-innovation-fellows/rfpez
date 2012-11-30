<div id="add-edit-section-modal" class="modal hide" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button class="close" type="button" data-dismiss="modal">×</button>
    <h3>&nbsp;</h3>
  </div>
  <div class="modal-body">
    <ul id="add-edit-section-tabs" class="nav nav-tabs">
      <li class="active section-library-li">
        <a href="#section-library" data-toggle="tab">Add From Library</a>
      </li>
      <li class="section-form-li">
        <a href="#section-form" data-toggle="tab">Add Custom Section</a>
      </li>
    </ul>
    <div class="tab-content">
      <div id="section-library" class="tab-pane active">
        <h5>
          Available Sections
          <input id="available-sections-filter" class="search-query pull-right" type="text" placeholder="Filter by category, title, body" />
        </h5>
        <table class="table available-sections-table" data-project-id="<?php echo e($project->id); ?>">
          <thead>
            <tr>
              <th width="50%" colspan="2">Title</th>
              <th width="20%">Category</th>
              <th width="15%">Times Used</th>
              <th width="15%">Use</th>
            </tr>
          </thead>
          <?php echo View::make('projects.partials.available_sections_tbody')->with('project', $project)->with('available_sections', $available_sections); ?>
          <tbody class="no-sections hide">
            <tr>
              <td colspan="5">No sections found.</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div id="section-form" class="tab-pane">
        <form id="edit-section-form" action="<?php echo e(route('project_sections', array($project->id))); ?>" method="POST">
          <input type="hidden" name="section_id" />
          <div class="control-group">
            <label class="control-label">Category</label>
            <div class="controls category-controls">
              <select id="section-category-select">
                <?php foreach (ProjectSection::$categories as $category): ?>
                  <option value="<?php echo e($category); ?>"><?php echo e($category); ?></option>
                <?php endforeach; ?>
                <option value="Other">Other</option>
              </select>
              <input id="section-category-input" type="text" name="project_section[section_category]" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Subheader</label>
            <div class="controls">
              <input type="text" name="project_section[title]" />
            </div>
          </div>
          <div class="control-group">
            <div class="controls">
              <div class="wysiwyg-wrapper">
                <textarea class="wysihtml5" name="project_section[body]"></textarea>
              </div>
            </div>
          </div>
          <div class="form-actions">
            <span class="will-fork pull-left">&nbsp;</span>
            <button class="btn" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary save-button" data-loading-text="Saving...">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>