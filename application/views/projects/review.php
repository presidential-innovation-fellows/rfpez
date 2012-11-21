<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Review") ?>
<?php Section::inject('active_subnav', 'create') ?>
<?php Section::inject('active_sidebar', 'review') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="row-fluid">
  <div class="span3">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.sow_composer_sidebar')->with('project', $project)); ?>
  </div>
  <div class="span9">
    <div class="well">
      Everything look good? If you're a contracting officer, you can now
      <a href="<?php echo Jade\Dumper::_text(route('project_post_on_fbo', array($project->id))); ?>">post this project</a>
      on FedBizOpps. If not, you can <a href="<?php echo Jade\Dumper::_text(route('project_admin', array($project->id))); ?>">invite your CO</a>
      to collaborate with you on this project and they can help you get it out the door.
    </div>
    <?php echo Jade\Dumper::_html(View::make('projects.partials.full_sow')->with('project', $project)); ?>
    <div class="form-actions">
      <a class="btn btn-primary" href="<?php echo Jade\Dumper::_text(route('project_post_on_fbo', array($project->id))); ?>">Looks Good! &rarr;</a>
    </div>
  </div>
</div>