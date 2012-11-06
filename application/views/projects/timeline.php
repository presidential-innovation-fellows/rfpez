<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Timeline") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'timeline') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.sow_composer_sidebar')->with('project', $project)); ?>
  </div>
  <div class="span9">
    <form method="POST">
      <table class="table timeline-table">
        <thead>
          <tr>
            <th>Deliverable</th>
            <th>Completion Date <?php echo Jade\Dumper::_html(helper_tooltip("Feel free to assign a date as 'TBD' or blank if you're not sure yet.")); ?></th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="deliverables-tbody">
          <script type="text/javascript">
            $(function(){
              new Rfpez.Backbone.SowDeliverables( <?php echo Jade\Dumper::_text($project->id); ?>, <?php echo Jade\Dumper::_text($deliverables_json); ?> )
            })
          </script>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3">
              <a class="btn add-deliverable-button pull-right">Add Deliverable <i class="icon-plus-sign"></i></a>
            </td>
          </tr>
        </tfoot>
      </table>
      <div class="form-actions">
        <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_review', array($project->id))); ?>">Save and Continue &rarr;</a>
      </div>
    </form>
  </div>
</div>