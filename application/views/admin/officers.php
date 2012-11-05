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
      <th>actions</th>
    </tr>
  </thead>
  <tbody id="officers-tbody">
    <script type="text/javascript">
      $(function(){
       new Rfpez.Backbone.AdminOfficers( <?php echo Jade\Dumper::_text($officers_json); ?> )
      })
    </script>
  </tbody>
</table>
<div class="pagination-wrapper">
  <?php echo Jade\Dumper::_html($officers->links()); ?>
</div>