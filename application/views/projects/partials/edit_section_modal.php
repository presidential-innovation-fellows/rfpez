<div id="edit-section-modal" class="modal hide" tabindex="-1" role="dialog">
  <form id="edit-section-form" class="form-horizontal" action="<?php echo Jade\Dumper::_text(route('project_sections_edit', array($project->id))); ?>" method="POST">
    <input type="hidden" name="section_id" />
    <div class="modal-header">
      <button class="close" type="button" data-dismiss="modal">×</button>
      <h3>&nbsp;</h3>
    </div>
    <div class="modal-body">
      <div class="control-group">
        <label class="control-label">Section Category</label>
        <div class="controls">
          <input type="text" name="project_section[section_category]" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Title</label>
        <div class="controls">
          <input type="text" name="project_section[title]" />
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <textarea name="project_section[body]" placeholder="Body"></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal">Cancel</button>
      <button class="btn btn-primary save-button" data-loading-text="Saving...">Save</button>
    </div>
  </form>
</div>