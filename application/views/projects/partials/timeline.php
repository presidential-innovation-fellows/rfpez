<h5>Timeline</h5>
<table class="table timeline-table">
  <thead>
    <tr>
      <th>Deliverable</th>
      <th>Completion Date</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($project->deliverables as $deliverable => $date): ?>
      <tr>
        <td><?php echo Jade\Dumper::_text($deliverable); ?></td>
        <td><?php echo Jade\Dumper::_text($date); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>