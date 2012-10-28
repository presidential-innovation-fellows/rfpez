<h4>Background</h4>
<p><?php echo Jade\Dumper::_html($project->background); ?></p>
<?php foreach ($project->sections_by_category() as $category => $sections): ?>
  <h4><?php echo Jade\Dumper::_text($category); ?></h4>
  <?php foreach ($sections as $section): ?>
    <strong><?php echo Jade\Dumper::_text($section->title); ?></strong>
    <p><?php echo Jade\Dumper::_text($section->body); ?></p>
  <?php endforeach; ?>
<?php endforeach; ?>