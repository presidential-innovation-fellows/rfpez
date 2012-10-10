<?php
  $deliverables = $sow->sections('Deliverables');
?>

<?php if ($sow->background_and_scope()): ?>
  <h3>Background & Scope</h3>
  <p>
    <?= $sow->background_and_scope() ?>
  </p>
<?php endif; ?>

<?php foreach($sow->sow_section_types() as $section_type): ?>
  <h3><?= $section_type ?></h3>
  <?php $i = 1; foreach($sow->sections($section_type) as $section): ?>
    <h4><?= $i ?>) <?= $section->best_title() ?></h4>
    <p>
      <?php if ($section->template_section): ?>
        <?= $section->template_section->body ?>
      <?php else: ?>
        <?= $section->body ?>
      <?php endif; ?>
    </p>
  <?php ++$i; endforeach; ?>
<?php endforeach; ?>


<?php if(count($deliverables) > 0): ?>
  <h3>Timeline</h3>
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Deliverable</th>
        <th>Due Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($deliverables as $deliverable): ?>
        <tr>
          <td><?= $deliverable->best_title() ?></td>
          <td><?= $sow->due_date($deliverable) ? $sow->due_date($deliverable) : "TBD" ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>