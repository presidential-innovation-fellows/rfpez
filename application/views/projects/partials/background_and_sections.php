<h5>Background</h5>
<p><?php echo Jade\Dumper::_html($project->background); ?></p>
<?php foreach ($project->sections_by_category() as $category => $sections): ?>
  <h5><?php echo Jade\Dumper::_text($category); ?></h5>
  <?php foreach ($sections as $section): ?>
    <strong><?php echo Jade\Dumper::_text($section->title); ?></strong>
    <p><?php echo Jade\Dumper::_html($section->body); ?></p>
  <?php endforeach; ?>
<?php endforeach; ?>