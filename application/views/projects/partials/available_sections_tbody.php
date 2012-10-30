<tbody class="available-sections-tbody">
  <?php foreach ($available_sections as $section): ?>
    <tr class="section" data-section-id="<?php echo Jade\Dumper::_text($section->id); ?>">
      <td><?php echo Jade\Dumper::_text($section->title); ?></td>
      <td><?php echo Jade\Dumper::_text($section->section_category); ?></td>
      <td><?php echo Jade\Dumper::_text($section->times_used); ?></td>
      <td>
        <a class="btn btn-primary btn-mini add-button" data-href="<?php echo Jade\Dumper::_text(route('project_section_add', array($project->id, $section->id))); ?>">Use This &rarr;</a>
      </td>
    </tr>
  <?php endforeach; ?>
</tbody>