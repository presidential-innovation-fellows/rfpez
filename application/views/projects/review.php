<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Review") ?>
<?php Section::inject('active_subnav', 'review') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span8">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.full_sow')->with('project', $project)); ?>
  </div>
  <div class="span4">
    <h4>Sharing</h4>
    <h4>Download</h4>
    <h4>Next Steps</h4>
  </div>
</div>