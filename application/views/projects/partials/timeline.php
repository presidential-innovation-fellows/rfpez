<h5>Timeline</h5>
<table class="table timeline-table">
  <thead>
    <tr>
      <th>Deliverable</th>
      <th>Completion Date</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($project->deliverables): ?>
      <?php foreach ($project->deliverables as $deliverable): ?>
        <tr>
          <td><?php echo Jade\Dumper::_text($deliverable->name); ?></td>
          <td><?php echo Jade\Dumper::_text($deliverable->date_or_length()); ?></td>
        </tr>
      <?php endforeach; ?>
    <?php endif; ?>
  </tbody>
</table>