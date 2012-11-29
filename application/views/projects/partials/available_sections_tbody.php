<?php foreach ($available_sections as $section): ?>
  <tbody class="section" data-section-id="<?php echo $section->id; ?>">
    <tr class="section-info">
      <td><?php echo $section->title; ?></td>
      <td>
        <a class="preview-link">(preview)</a>
      </td>
      <td><?php echo $section->section_category; ?></td>
      <td><?php echo $section->times_used; ?></td>
      <td>
        <a class="btn btn-primary btn-mini add-button" data-href="<?php echo route('project_section_add', array($project->id, $section->id)); ?>">Use This &rarr;</a>
      </td>
    </tr>
    <tr class="preview hide">
      <td colspan="5"><?php echo $section->body; ?></td>
    </tr>
  </tbody>
<?php endforeach; ?>