<div class="sections-for-editing-wrapper" data-project-id="<?php echo e($project->id); ?>">
  <div class="sections-for-editing">
    <?php if ($sections_by_category = $project->sections_by_category()): ?>
      <?php foreach ($sections_by_category as $category => $sections): ?>
        <div class="category" data-name="<?php echo e($category); ?>">
          <h5><?php echo e($category); ?></h5>
          <div class="category-sections">
            <?php foreach ($sections as $section): ?>
              <div class="section" data-section-id="<?php echo e($section->id); ?>" data-section-title="<?php echo e($section->title); ?>" data-will-fork="<?php echo e($section->can_edit_without_forking() ? 'false' : 'true'); ?>">
                <strong><?php echo e($section->title); ?></strong>
                <a class="btn btn-success btn-mini edit-section-link" href="#">Edit</a>
                <a class="btn btn-danger btn-mini remove-button" data-href="<?php echo e(route('project_section_delete', array($project->id, $section->id))); ?>" data-loading-text="Removing...">Remove</a>
                <p class="body"><?php echo $section->body; ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p><?php echo e(__("r.projects.partials.sections_for_editing.none")); ?></p>
    <?php endif; ?>
  </div>
</div>