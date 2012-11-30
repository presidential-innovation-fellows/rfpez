<?php if ($project->price_type == Project::PRICE_TYPE_FIXED && ($deliverables = $project->non_blank_deliverables) && count($deliverables) != 0): ?>
  <h5>Timeline</h5>
  <table class="table timeline-table">
    <thead>
      <tr>
        <th>Deliverable</th>
        <th>Completion Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($deliverables as $deliverable): ?>
        <tr>
          <td><?php echo e($deliverable->name); ?></td>
          <td><?php echo e($deliverable->date_or_length()); ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>