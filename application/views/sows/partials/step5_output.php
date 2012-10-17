<h3>Background & Scope</h3>
<p><?php echo Jade\Dumper::_text($sow->background_and_scope()); ?></p>
<?php foreach($sow->sow_section_types() as $section_type): ?>
  <h3><?php echo Jade\Dumper::_text($section_type); ?></h3>
  <?php $i = 1 ?>
  <?php foreach($sow->sections($section_type) as $section): ?>
    <h4><?php echo Jade\Dumper::_text($i); ?> <?php echo Jade\Dumper::_text($section->best_title()); ?></h4>
    <p>
      <?php if ($section->template_section): ?>
        <?php echo Jade\Dumper::_text($section->template_section->body); ?>
      <?php else: ?>
        <textarea class="composer" name="custom_sections[<?php echo Jade\Dumper::_text($section->id); ?>]"><?php echo Jade\Dumper::_text($section->body); ?></textarea>
      <?php endif; ?>
    </p>
    <?php ++$i; ?>
  <?php endforeach; ?>
<?php endforeach; ?>