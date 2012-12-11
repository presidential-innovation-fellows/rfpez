<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Admin") ?>
<?php Section::inject('active_subnav', 'admin') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<div class="row-fluid">
  <div class="span6">
    <h5>Update Project</h5>
    <form id="update-project-form" action="<?php echo e(route('project', array($project->id))); ?>" method="POST">
      <input type="hidden" name="_method" value="PUT" />
      <div class="control-group">
        <label>Project Title</label>
        <input type="text" name="project[title]" value="<?php echo e($project->title); ?>" />
      </div>
      <div class="control-group">
        <label>Agency</label>
        <input type="text" name="project[agency]" value="<?php echo e($project->agency); ?>" />
      </div>
      <div class="control-group">
        <label>Office</label>
        <input type="text" name="project[office]" value="<?php echo e($project->office); ?>" />
      </div>
      <div class="control-group">
        <label>Project Type</label>
        <input type="text" value="<?php echo e($project->project_type->name); ?>" readonly="readonly" />
      </div>
      <div class="control-group">
        <label>Q+A Period Ends</label>
        <span class="input-append date datepicker-wrapper">
          <input class="span3" type="text" name="project[question_period_over_at]" value="<?php echo e($project->formatted_question_period_over_at()); ?>" />
          <span class="add-on">
            <i class="icon-calendar"></i>
          </span>
        </span>
        &nbsp; at 11:59pm EST
      </div>
      <div class="control-group">
        <label>Bids Due</label>
        <span class="input-append date datepicker-wrapper">
          <input class="span3" type="text" name="project[proposals_due_at]" value="<?php echo e($project->formatted_proposals_due_at()); ?>" />
          <span class="add-on">
            <i class="icon-calendar"></i>
          </span>
        </span>
        &nbsp; at 11:59pm EST
      </div>
      <div class="control-group">
        <label>Price type</label>
        <label>
          <input type="radio" name="project[price_type]" value="<?php echo e(Project::PRICE_TYPE_FIXED); ?>" <?php echo e($project->price_type == Project::PRICE_TYPE_FIXED ? 'checked' : ''); ?> />
          Fixed price
        </label>
        <label>
          <input type="radio" name="project[price_type]" value="<?php echo e(Project::PRICE_TYPE_HOURLY); ?>" <?php echo e($project->price_type == Project::PRICE_TYPE_HOURLY ? 'checked' : ''); ?> />
          Hourly price
        </label>
        <?php if ($project->submitted_bids()->count() > 0): ?>
          <em><?php echo e(__("r.projects.admin.change_price_type_warning")); ?></em>
        <?php endif; ?>
      </div>
      <div class="form-actions">
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="span6">
    <h5>Collaborators</h5>
    <p><?php echo e(__("r.projects.admin.collaborators")); ?></p>
    <table class="table collaborators-table" data-project-id="<?php echo e($project->id); ?>">
      <thead>
        <tr>
          <th>Email</th>
          <th>Owner</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody id="collaborators-tbody">
        <script type="text/javascript">
          $(function(){
           new Rfpez.Backbone.Collaborators( <?php echo $project->id; ?>, <?php echo $project->owner()->user->id; ?>, <?php echo $collaborators_json; ?> )
          })
        </script>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3">
            <form id="add-collaborator-form" action="<?php echo e(route('project_collaborators', array($project->id))); ?>" method="POST">
              <div class="input-append">
                <input type="text" name="email" placeholder="Email Address" autocomplete="off" />
                <button class="btn btn-success">Add</button>
              </div>
            </form>
          </td>
        </tr>
      </tfoot>
    </table>
    <h5>Sharing</h5>
    <p>
      <?php echo e(__("r.projects.admin.sharing")); ?>
      <form action="<?php echo e(route('project_toggle_public', array($project->id))); ?>?redirect=<?php echo e(URI::current()); ?>" method="POST">
        <div class="well">
          <?php if ($project->public): ?>
            <span class="public-status">Status: Public</span>
            <button class="btn btn-danger">Set to Private</button>
          <?php else: ?>
            <span class="public-status">Status: Private</span>
            <button class="btn btn-success">Set to Public (Recommended!)</button>
          <?php endif; ?>
        </div>
      </form>
    </p>
  </div>
</div>