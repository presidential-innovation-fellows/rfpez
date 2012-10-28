<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Timeline") ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<h4>Timeline</h4>
<form method="POST">
  <table class="table timeline-table">
    <thead>
      <tr>
        <th>Deliverable</th>
        <th>Completion Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($deliverables as $deliverable => $date): ?>
        <tr>
          <td>
            <input type="text" name="deliverables[]" placeholder="Deliverable Name" value="<?php echo Jade\Dumper::_text($deliverable); ?>" />
          </td>
          <td>
            <input type="text" name="deliverable_dates[]" value="<?php echo Jade\Dumper::_text($date); ?>" />
            <a class="btn btn-success add-deliverable-button">Add</a>
            <a class="btn remove-deliverable-button">
              <i class="icon-trash"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    <tfoot>
      <tr class="add-deliverable-row">
        <td>
          <input type="text" name="deliverables[]" placeholder="Deliverable Name" data-original-val="" />
        </td>
        <td>
          <input type="text" name="deliverable_dates[]" value="today" data-original-val="today" />
          <a class="btn btn-success add-deliverable-button">Add</a>
          <a class="btn remove-deliverable-button">
            <i class="icon-trash"></i>
          </a>
        </td>
      </tr>
    </tfoot>
  </table>
  <div class="form-actions">
    <button class="btn btn-primary">Next &rarr;</button>
  </div>
</form>