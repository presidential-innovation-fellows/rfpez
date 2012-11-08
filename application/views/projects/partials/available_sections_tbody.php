<?php foreach ($available_sections as $section): ?>
  <tbody class="section" data-section-id="<?php echo Jade\Dumper::_text($section->id); ?>">
    <tr class="section-info">
      <td><?php echo Jade\Dumper::_text($section->title); ?></td>
      <td>
        <a class="preview-link">(preview)</a>
      </td>
      <td><?php echo Jade\Dumper::_text($section->section_category); ?></td>
      <td><?php echo Jade\Dumper::_text($section->times_used); ?></td>
      <td>
        <a class="btn btn-primary btn-mini add-button" data-href="<?php echo Jade\Dumper::_text(route('project_section_add', array($project->id, $section->id))); ?>">Use This &rarr;</a>
      </td>
    </tr>
    <tr class="preview hide">
      <td colspan="5"><?php echo Jade\Dumper::_text($section->body); ?></td>
    </tr>
  </tbody>
<?php endforeach; ?>