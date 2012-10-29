<?php Section::inject('page_title', 'New Project') ?>
<form id="new-project-form" action="<?php echo Jade\Dumper::_text(route('projects')); ?>" method="POST">
  <div class="control-group">
    <label>Project Title</label>
    <input type="text" name="project[title]" />
  </div>
  <div class="control-group">
    <label>Agency</label>
    <input type="text" name="project[agency]" />
  </div>
  <div class="control-group">
    <label>Office</label>
    <input type="text" name="project[office]" />
  </div>
  <div class="control-group">
    <label>Project Type</label>
    <select name="project[project_type_id]">
      <?php foreach (ProjectType::all() as $project_type): ?>
        <option value="<?php echo Jade\Dumper::_text($project_type->id); ?>"><?php echo Jade\Dumper::_text($project_type->name); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button class="btn btn-primary" type="submit">Create</button>
</form>