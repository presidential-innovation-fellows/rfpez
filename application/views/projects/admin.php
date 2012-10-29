<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Admin") ?>
<?php Section::inject('active_subnav', 'admin') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span6">
    <h3>Update Project</h3>
    <form id="update-project-form" action="<?php echo Jade\Dumper::_text(route('project', array($project->id))); ?>" method="POST">
      <input type="hidden" name="_method" value="PUT" />
      <div class="control-group">
        <label>Project Title</label>
        <input type="text" name="project[title]" value="<?php echo Jade\Dumper::_text($project->title); ?>" />
      </div>
      <div class="control-group">
        <label>Agency</label>
        <input type="text" name="project[agency]" value="<?php echo Jade\Dumper::_text($project->agency); ?>" />
      </div>
      <div class="control-group">
        <label>Office</label>
        <input type="text" name="project[office]" value="<?php echo Jade\Dumper::_text($project->office); ?>" />
      </div>
      <div class="control-group">
        <label>Project Type</label>
        <input type="text" value="<?php echo Jade\Dumper::_text($project->project_type->name); ?>" readonly="readonly" />
      </div>
      <div class="control-group">
        <label>Bids Due At</label>
        <span class="input-append date datepicker">
          <input class="span3" type="text" name="project[proposals_due_at]" value="<?php echo Jade\Dumper::_text($project->formatted_proposals_due_at()); ?>" />
          <span class="add-on">
            <i class="icon-calendar"></i>
          </span>
        </span>
        at 11:59pm EST
      </div>
      <div class="form-actions">
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
  <div class="span6">
    <h3>Collaborators</h3>
    <p>Invite any registered government employee to collaborate on this project.</p>
    <table class="table collaborators-table" data-project-id="<?php echo Jade\Dumper::_text($project->id); ?>">
      <thead>
        <tr>
          <th>Email</th>
          <th>Owner</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($project->officers as $officer): ?>
          <?php echo Jade\Dumper::_html(View::make('projects.partials.collaborator_tr')->with('officer', $officer)->with('project', $project)); ?>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3">
            <form id="add-collaborator-form" action="<?php echo Jade\Dumper::_text(route('project_collaborators', array($project->id))); ?>" method="POST">
              <div class="input-append">
                <input type="text" name="email" placeholder="Email Address" autocomplete="off" />
                <button class="btn btn-success">Add</button>
              </div>
            </form>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>