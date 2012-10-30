<?php Section::inject('page_title', 'New Project') ?>
<?php Section::inject('no_page_header', true) ?>
<div class="new-project-page">
  <h4>New Project</h4>
  <div class="well">
    <p>
      <strong>Congrats on using EasyBid for your procurement!</strong>
    </p>
    <p>
      First, we just need some basic information about the project you're doing.
      If you can't find the correct project type in the list, <a href="#">contact us</a>
      and let us know.
    </p>
  </div>
  <form id="new-project-form" class="form-big" action="<?php echo Jade\Dumper::_text(route('projects')); ?>" method="POST">
    <div class="control-group">
      <input type="text" name="project[title]" placeholder="Project Title" />
    </div>
    <div class="control-group">
      <input type="text" name="project[agency]" placeholder="Agency" />
    </div>
    <div class="control-group">
      <input type="text" name="project[office]" placeholder="Office" />
    </div>
    <div class="control-group">
      <select name="project[project_type_id]">
        <option value="">-- Select Project Type --</option>
        <?php foreach (ProjectType::all() as $project_type): ?>
          <option value="<?php echo Jade\Dumper::_text($project_type->id); ?>"><?php echo Jade\Dumper::_text($project_type->name); ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="form-actions">
      <button class="btn btn-primary" type="submit">Create Project</button>
    </div>
  </form>
</div>