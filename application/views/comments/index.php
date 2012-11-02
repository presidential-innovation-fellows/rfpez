<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('page_action', "Comments") ?>
<?php Section::inject('active_subnav', 'comments') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="comments-list">
  <?php foreach ($comments as $comment) { ?>
    <?php echo Jade\Dumper::_html(View::make('comments.partials.comment')->with('comment', $comment)); ?>
  <?php } ?>
</div>
<div class="form-actions">
  <h5>Add Comment</h5>
  <form id="add-comment-form" action="<?php echo Jade\Dumper::_text('comments'); ?>" method="POST">
    <textarea class="span5" name="body"></textarea>
    <div>
      <button class="btn btn-primary">Submit Comment</button>
    </div>
  </form>
</div>