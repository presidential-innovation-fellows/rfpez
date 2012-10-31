<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('admin.partials.subnav')->with('current_page', 'officers')); ?>
<table class="table table-bordered table-striped admin-officers-table">
  <thead>
    <tr>
      <th>id</th>
      <th>name</th>
      <th>title</th>
      <th>email</th>
      <th>role</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($officers->results as $officer): ?>
      <tr>
        <td><?php echo Jade\Dumper::_text($officer->id); ?></td>
        <td><?php echo Jade\Dumper::_text($officer->name); ?></td>
        <td><?php echo Jade\Dumper::_text($officer->title); ?></td>
        <td><?php echo Jade\Dumper::_text($officer->user->email); ?></td>
        <td>
          <?php echo Jade\Dumper::_text($officer->role_text()); ?>
          <?php if ($officer->role == Officer::ROLE_PROGRAM_OFFICER): ?>
            &nbsp;&nbsp;
            <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('admin_verify_contracting_officer', array($officer->id))); ?>">Verify as CO</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<div class="pagination-wrapper">
  <?php echo Jade\Dumper::_html($officers->links()); ?>
</div>