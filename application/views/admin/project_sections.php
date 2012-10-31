<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('admin.partials.subnav')->with('current_page', 'project_sections')); ?>
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
        <td><?php echo Jade\Dumper::_text($project_section->id); ?></td>
        <td><?php echo Jade\Dumper::_text($project_section->based_on_project_section_id); ?></td>
        <td><?php echo Jade\Dumper::_text($project_section->created_by_project_id); ?></td>
        <td><?php echo Jade\Dumper::_text($project_section->times_used); ?></td>
        <td><?php echo Jade\Dumper::_text($project_section->section_category); ?></td>
        <td><?php echo Jade\Dumper::_text($project_section->title); ?></td>
        <td><?php echo Jade\Dumper::_text($project_section->body); ?></td>
        <td><?php echo Jade\Dumper::_text($project_section->public); ?></td>
        <td><?php echo Jade\Dumper::_text(date('m/d/y', strtotime($project_section->created_at))); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div class="pagination-wrapper">
  <?php echo Jade\Dumper::_html($project_sections->links()); ?>
</div>