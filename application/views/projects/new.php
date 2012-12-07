<?php Section::inject('page_title', 'New Project') ?>
<?php Section::inject('no_page_header', true) ?>
<div class="new-project-page">
  <h4>New Project</h4>
  <div class="well">
    <p>
      <strong><?php echo __("r.projects.new.congrats"); ?></strong>
    </p>
    <p><?php echo __("r.projects.new.helper"); ?></p>
  </div>
  <form id="new-project-form" action="<?php echo e(route('projects')); ?>" method="POST">
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
      <label>Bids Due</label>
      <span class="input-append date datepicker-wrapper">
        <input class="span3" type="text" name="project[proposals_due_at]" />
        <span class="add-on">
          <i class="icon-calendar"></i>
        </span>
      </span>
      &nbsp; at 11:59pm EST
      <p>
        <em><?php echo __("r.projects.new.no_date"); ?></em>
      </p>
    </div>
    <div class="control-group">
      <label>Project Type</label>
      <select id="project-type-select" name="project[project_type_id]">
        <?php foreach (ProjectType::defaults() as $project_type): ?>
          <option value="<?php echo e($project_type->id); ?>"><?php echo e($project_type->name); ?></option>
        <?php endforeach; ?>
        <option value="Other">Other</option>
      </select>
      <input id="new-project-type-input" class="hide" type="text" name="new_project_type_name" />
    </div>
    <div class="control-group">
      <label>Price type</label>
      <label class="radio">
        <input type="radio" name="project[price_type]" value="<?php echo e(Project::PRICE_TYPE_FIXED); ?>" checked="checked" />
        Fixed price
      </label>
      <label class="radio">
        <input type="radio" name="project[price_type]" value="<?php echo e(Project::PRICE_TYPE_HOURLY); ?>" />
        Hourly price
      </label>
    </div>
    <div class="form-actions">
      <button class="btn btn-primary" type="submit">Create Project</button>
    </div>
  </form>
</div>