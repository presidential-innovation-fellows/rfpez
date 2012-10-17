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
    <label>Naics</label>
    <input type="text" name="project[naics_code]" />
  </div>
  <div class="control-group">
    <label>SOW Template</label>
    <select name="template_id">
      <?php foreach (SowTemplate::current()->get() as $template): ?>
        <option value="<?php echo Jade\Dumper::_text($template->id); ?>"><?php echo Jade\Dumper::_text($template->title); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <button class="btn btn-primary" type="submit">Create</button>
</form>