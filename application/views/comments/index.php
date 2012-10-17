<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('page_action', "Comments") ?>
<?php Section::inject('active_subnav', 'comments') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<div class="comments-list">
  <?php foreach ($project->comments as $comment) { ?>
    <?php echo Jade\Dumper::_html(View::make('comments.partials.comment')->with('comment', $comment)); ?>
  <?php } ?>
</div>
<h4>Add Comment</h4>
<form id="add-comment-form" action="<?php echo Jade\Dumper::_text('comments'); ?>" method="POST">
  <textarea name="body"></textarea>
  <div class="form-actions">
    <button class="btn btn-primary">Submit Comment</button>
  </div>
</form>