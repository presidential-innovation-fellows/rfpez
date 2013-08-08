<h3 class="sow-section-header sow-section-header-first">Background</h3>
<p class="p-sow-background"><?php echo $project->background; ?></p>
<?php foreach ($project->sections_by_category() as $category => $sections): ?>
  <h3 class="sow-section-header"><?php echo e($category); ?></h3>
  <?php foreach ($sections as $section): ?>
    <strong><?php echo e($section->title); ?></strong>
    <p><?php echo $section->body; ?></p>
  <?php endforeach; ?>
<?php endforeach; ?>