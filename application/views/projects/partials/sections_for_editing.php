<div class="sections-for-editing">
  <?php foreach ($project->sections_by_category() as $category => $sections): ?>
    <div class="category" data-name="<?php echo Jade\Dumper::_text($category); ?>">
      <h4><?php echo Jade\Dumper::_text($category); ?></h4>
      <?php foreach ($sections as $section): ?>
        <div class="section" data-section-id="<?php echo Jade\Dumper::_text($section->id); ?>">
          <strong><?php echo Jade\Dumper::_text($section->title); ?></strong>
          <p><?php echo Jade\Dumper::_text($section->body); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endforeach; ?>
</div>