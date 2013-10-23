<div class="subheader">
  <?php Section::inject('page_title', $project->title) ?>
  <?php Section::inject('page_action', "Admin") ?>
  <?php Section::inject('active_subnav', 'admin') ?>
  <?php Section::inject('no_page_header', true) ?>
  <?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
</div>
<div class="container inner-container">
  <div class="row-fluid">
    <div class="span6">
      <h5>Update Project</h5>
      <form id="update-project-form" action="<?php echo e(route('project', array($project->id))); ?>" method="POST">
        <input type="hidden" name="_method" value="PUT" />
        <?php if (Auth::user()): ?>
          <?php if (Auth::officer() && Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)): ?>
            <div class="control-group">
              <label>Source</label>
              <select id="source-select" name="project[source]">
                <?php if ($project->source == Project::SOURCE_NATIVE): ?>
                  <option value="<?php echo e(Project::SOURCE_NATIVE); ?>" selected="selected">RFP-EZ</option>
                <?php else: ?>
                  <option value="<?php echo e(Project::SOURCE_NATIVE); ?>">RFP-EZ</option>
                <?php endif; ?>
                <?php if ($project->source == Project::SOURCE_FBO): ?>
                  <option value="<?php echo e(Project::SOURCE_FBO); ?>" selected="selected">FBO</option>
                <?php else: ?>
                  <option value="<?php echo e(Project::SOURCE_FBO); ?>">FBO</option>
                <?php endif; ?>
                <?php if ($project->source == Project::SOURCE_CHALLENGEGOV): ?>
                  <option value="<?php echo e(Project::SOURCE_CHALLENGEGOV); ?>" selected="selected">Challenge</option>
                <?php else: ?>
                  <option value="<?php echo e(Project::SOURCE_CHALLENGEGOV); ?>">Challenge</option>
                <?php endif; ?>
                <?php if ($project->source == Project::SOURCE_SBIR): ?>
                  <option value="<?php echo e(Project::SOURCE_SBIR); ?>" selected="selected">SBIR</option>
                <?php else: ?>
                  <option value="<?php echo e(Project::SOURCE_SBIR); ?>">SBIR</option>
                <?php endif; ?>
              </select>
            </div>
            <div class="control-group">
              <label>External URL <em>(if source is not RFP-EZ)</em></label>
              <input class="full-width" type="text" name="project[external_url]" value="<?php echo e($project->external_url); ?>" />
            </div>
          <?php endif; ?>
        <?php endif; ?>
        <div class="control-group">
          <label>Project Title</label>
          <input class="full-width" type="text" name="project[title]" value="<?php echo e($project->title); ?>" />
        </div>
        <div class="control-group">
          <label>Agency</label>
          <input class="full-width" type="text" name="project[agency]" value="<?php echo e($project->agency); ?>" />
        </div>
        <div class="control-group">
          <label>Office</label>
          <input class="full-width" type="text" name="project[office]" value="<?php echo e($project->office); ?>" />
        </div>
        <div class="control-group">
          <label>Zip Code</label>
          <input class="full-width" type="text" name="project[zipcode]" value="<?php echo e($project->zipcode); ?>" />
        </div>
        <div class="control-group">
          <label>Project Type</label>
          <input class="full-width" type="text" value="<?php echo e($project->project_type->name); ?>" readonly="readonly" />
        </div>
        <div class="control-group">
          <label>Q+A Period Ends</label>
          <span class="input-append date datetimepicker-wrapper">
            <input type="text" name="project[question_period_over_at]" value="<?php echo e($project->formatted_question_period_over_at()); ?>" />
            <span class="add-on">
              <i class="icon-calendar"></i>
            </span>
          </span>
        </div>
        <div class="control-group">
          <label>Bids Due</label>
          <span class="input-append date datetimepicker-wrapper">
            <input type="text" name="project[proposals_due_at]" value="<?php echo e($project->formatted_proposals_due_at()); ?>" />
            <span class="add-on">
              <i class="icon-calendar"></i>
            </span>
          </span>
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
          <?php if (Auth::user()): ?>
            <?php if (Auth::officer() && Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)): ?>
              <label>
                <input type="radio" name="project[price_type]" value="<?php echo e(Project::PRICE_TYPE_NONE); ?>" <?php echo e($project->price_type == Project::PRICE_TYPE_NONE ? 'checked' : ''); ?> />
                NONE (external project)
              </label>
            <?php endif; ?>
          <?php endif; ?>
          <?php if ($project->submitted_bids()->count() > 0): ?>
            <em><?php echo e(__("r.projects.admin.change_price_type_warning")); ?></em>
          <?php endif; ?>
        </div>
        <?php if (Auth::user()): ?>
          <?php if (Auth::officer() && Auth::officer()->is_role_or_higher(Officer::ROLE_SUPER_ADMIN)): ?>
            <div class="control-group background-edit-form">
              <br /><strong>Background:</strong>
              <div class="wysiwyg-wrapper">
                <textarea class="wysihtml5" name="project[background]"><?php echo $project->background; ?></textarea>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>
        <div class="control-group">
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
                  <input class="full-width" type="text" name="email" placeholder="Email Address" autocomplete="off" />
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
      <?php if ($project->status() != Project::STATUS_AMENDING_SOW && $project->status() != Project::STATUS_WRITING_SOW): ?>
        <br />
        <h5>Amending</h5>
        <p>Click the button below to begin amending your Statement of Work.</p>
        <div class="alert alert-info">
          Note: RFP-EZ does not retain previous versions of your SOW. 
          You may want to print or save your SOW in its current state before you begin amending it.
        </div>
        <form action="<?php echo e(route('project_begin_amending', array($project->id))); ?>" method="POST">
          <button class="btn btn-warning btn-large">Amend Project</button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>