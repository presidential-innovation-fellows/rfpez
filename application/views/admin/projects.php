<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('admin.partials.subnav')->with('current_page', 'projects')); ?>
<table class="table table-bordered table-striped admin-projects-table">
  <thead>
    <tr>
      <th>id</th>
      <th>title</th>
      <th>fork_count</th>
      <th>recommended</th>
      <th>
        public
        <?php echo Jade\Dumper::_html(Helper::helper_tooltip(__("r.admin.projects.project_helper_text"), "top")); ?>
      </th>
      <th>project_type</th>
    </tr>
  </thead>
  <tbody id="projects-tbody">
    <script type="text/javascript">
      $(function(){
       new Rfpez.Backbone.AdminProjects( <?php echo Jade\Dumper::_text($projects_json); ?> )
      })
    </script>
  </tbody>
</table>
<div class="pagination-wrapper">
  <?php echo Jade\Dumper::_html($projects->links()); ?>
</div>