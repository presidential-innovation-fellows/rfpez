<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Timeline") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'timeline') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo View::make('projects.partials.sow_composer_sidebar')->with('project', $project); ?>
  </div>
  <div class="span9">
    <div class="alert alert-info">
      <?php if ($project->price_type == Project::PRICE_TYPE_HOURLY): ?>
        <?php echo __("r.projects.timeline.hourly"); ?>
      <?php endif; ?>
      <?php echo __("r.projects.timeline.drag_helper"); ?>
    </div>
    <form method="POST">
      <table class="table timeline-table <?php echo e($project->price_type == Project::PRICE_TYPE_HOURLY ? 'hourly-price' : 'fixed-price'); ?>">
        <thead>
          <tr>
            <th>Deliverable</th>
            <th class="completion-date">Completion Date <?php echo Helper::helper_tooltip(__("r.projects.timeline.tbd")); ?></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="deliverables-tbody">
          <script type="text/javascript">
            $(function(){
              new Rfpez.Backbone.SowDeliverables( <?php echo $project->id; ?>, <?php echo $deliverables_json; ?> )
            })
          </script>
        </tbody>
      </table>
      <a class="btn add-deliverable-timeline-button pull-right">Add Deliverable <i class="icon-plus-sign"></i></a>
      <div class="clearfix"></div>
      <div class="form-actions">
        <a class="btn btn-primary" href="<?php echo e(route('project_review', array($project->id))); ?>">Save and Continue &rarr;</a>
      </div>
    </form>
  </div>
</div>