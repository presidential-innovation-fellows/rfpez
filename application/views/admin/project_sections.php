<?php Section::inject('no_page_header', true) ?>
<?php echo View::make('admin.partials.subnav')->with('current_page', 'project_sections'); ?>
<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Id</th>
      <th>Based on</th>
      <th>Created by</th>
      <th>Times Used</th>
      <th>Category</th>
      <th>Title</th>
      <th>Body</th>
      <th>Public?</th>
      <th>Created At</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($project_sections->results as $project_section): ?>
      <tr>
        <td><?php echo e($project_section->id); ?></td>
        <td><?php echo e($project_section->based_on_project_section_id); ?></td>
        <td><?php echo e($project_section->created_by_project_id); ?></td>
        <td><?php echo e($project_section->times_used); ?></td>
        <td><?php echo e($project_section->section_category); ?></td>
        <td><?php echo e($project_section->title); ?></td>
        <td><?php echo e($project_section->body); ?></td>
        <td>
          <?php if ($project_section->public): ?>
            <a class="btn btn-success" href="<?php echo e(route('admin_project_sections_toggle_public', array($project_section->id))); ?>" data-no-turbolink="data-no-turbolink">Public</a>
          <?php else: ?>
            <a class="btn" href="<?php echo e(route('admin_project_sections_toggle_public', array($project_section->id))); ?>" data-no-turbolink="data-no-turbolink">Private</a>
          <?php endif; ?>
        </td>
        <td><?php echo e(date('m/d/y', strtotime($project_section->created_at))); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div class="pagination-wrapper">
  <?php echo $project_sections->links(); ?>
</div>