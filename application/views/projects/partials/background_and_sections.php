<h5>Background</h5>
<p><?php echo $project->background; ?></p>
<?php foreach ($project->sections_by_category() as $category => $sections): ?>
  <h5><?php echo e($category); ?></h5>
  <?php foreach ($sections as $section): ?>
    <strong><?php echo e($section->title); ?></strong>
    <p><?php echo $section->body; ?></p>
  <?php endforeach; ?>
<?php endforeach; ?>