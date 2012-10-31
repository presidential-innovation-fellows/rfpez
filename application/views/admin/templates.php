<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('admin.partials.subnav')->with('current_page', 'templates')); ?>
<table class="table table-bordered table-striped admin-template-table">
  <thead>
    <tr>
      <th>id</th>
      <th>title</th>
      <th>fork_count</th>
      <th>recommended</th>
      <th>project_type</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($templates->results as $template): ?>
      <tr>
        <td><?php echo Jade\Dumper::_text($template->id); ?></td>
        <td><?php echo Jade\Dumper::_text($template->title); ?></td>
        <td><?php echo Jade\Dumper::_text($template->fork_count); ?></td>
        <td>
          <form action="<?php echo Jade\Dumper::_text(route('admin_template_toggle_recommended', array($template->id))); ?>" method="POST">
            <?php if ($template->recommended): ?>
              <button class="btn btn-success">Yes</button>
            <?php else: ?>
              <button class="btn">No</button>
            <?php endif; ?>
          </form>
        </td>
        <td><?php echo Jade\Dumper::_text($template->project_type->name); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div class="pagination-wrapper">
  <?php echo Jade\Dumper::_html($templates->links()); ?>
</div>