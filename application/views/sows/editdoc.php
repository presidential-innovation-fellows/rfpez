<?php Section::inject('page_title', $sow->title) ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('active_sidebar', 'editdoc') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $sow->project)); ?>
<form method="POST">
  <div class="row">
    <div class="span3">
      <?php echo Jade\Dumper::_html(View::make('sows.partials.sidebar')->with('project', $sow->project)); ?>
    </div>
    <div class="span9 step step-6">
      <textarea id="sow-content-wysiwyg" name="body" style="width: 100%; min-height: 400px">
        <?php if ($sow->body): ?>
          <?php echo Jade\Dumper::_html($sow->body); ?>
        <?php else: ?>
          <?php echo Jade\Dumper::_html(SowVariableParser::parse(View::make('sows.partials.step6_output')->with('sow', $sow), $sow, "read")); ?>
        <?php endif; ?>
      </textarea>
      <div class="bottom-controls well">
        <a class="btn" href="<?php echo Jade\Dumper::_text(route('sow_fillinblanks', array($sow->project->id))); ?>">&larr; Enter Variables</a>
        <button class="btn btn-primary pull-right">Download SOW &rarr;</button>
      </div>
    </div>
  </div>
</form>