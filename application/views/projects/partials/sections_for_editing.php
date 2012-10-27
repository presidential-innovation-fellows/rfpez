<div class="sections-for-editing-wrapper" data-project-id="<?php echo Jade\Dumper::_text($project->id); ?>">
  <div class="sections-for-editing">
    <?php foreach ($project->sections_by_category() as $category => $sections): ?>
      <div class="category" data-name="<?php echo Jade\Dumper::_text($category); ?>">
        <h4><?php echo Jade\Dumper::_text($category); ?></h4>
        <div class="category-sections">
          <?php foreach ($sections as $section): ?>
            <div class="section" data-section-id="<?php echo Jade\Dumper::_text($section->id); ?>" data-section-title="<?php echo Jade\Dumper::_text($section->title); ?>">
              <strong><?php echo Jade\Dumper::_text($section->title); ?></strong>
              <a class="edit-section-link" href="#">edit</a>
              <p class="body"><?php echo Jade\Dumper::_text($section->body); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>