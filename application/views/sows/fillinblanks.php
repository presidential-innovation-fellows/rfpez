<?php Section::inject('page_title', $sow->title) ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('active_sidebar', 'fillinblanks') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $sow->project)); ?>
<form method="POST">
  <div class="row">
    <div class="span3">
      <?php echo Jade\Dumper::_html(View::make('sows.partials.sidebar')->with('project', $sow->project)); ?>
    </div>
    <div class="span9 step step-5">
      <div class="alert alert-info">Fill in the blanks below and we'll assemble your final document, which you can edit in the next step.</div>
      <div class="sow-content">
        <?php echo Jade\Dumper::_html(SowVariableParser::parse(View::make('sows.partials.step5_output')->with('sow', $sow), $sow)); ?>
      </div>
      <div class="bottom-controls well">
        <?php $last_template_section_type = $sow->last_template_section_type() ?>
        <a class="btn" href="<?php echo Jade\Dumper::_text(route('sow_section', array($sow->project->id, $last_template_section_type))); ?>">
          &larr; <?php echo Jade\Dumper::_text($last_template_section_type); ?>
        </a>
        <button class="btn btn-primary pull-right">Edit Document &rarr;</button>
      </div>
    </div>
  </div>
</form>