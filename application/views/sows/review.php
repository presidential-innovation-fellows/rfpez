<?php Section::inject('page_title', $sow->project->title) ?>
<?php Section::inject('page_action', 'Review SOW') ?>
<?php Section::inject('active_subnav', 'review') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $sow->project)); ?>
<h1><?php echo Jade\Dumper::_text($sow->title); ?></h1>
<div class="row">
  <div class="span8 step step-7">
    <?php echo Jade\Dumper::_html($sow->body ?: "&nbsp;"); ?>
  </div>
  <div class="span3 well">
    <h4>Add Collaborators</h4>
    <p>In the admin tab you can add your CO and get this SOW out the door.</p>
    <h4>Post to FBO</h4>
    <p>Are you a contracting officer? If this SOW is ready, post it to FBO to get the EasyBid process rolling.</p>
    <a class="btn btn-info" href="<?php echo Jade\Dumper::_text(route('project_post_on_fbo', array($sow->project->id))); ?>" data-pjax="data-pjax">Post to FBO</a>
  </div>
</div>