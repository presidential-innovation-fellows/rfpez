<div class="well comment">
  <div class="body">
    <span class="author"><?php echo Jade\Dumper::_text($comment->officer->name); ?> -
</span>
    <?php echo Jade\Dumper::_text($comment->body); ?>
  </div>
  <span class="timestamp">
    <span class="posted-at">Posted <?php echo Jade\Dumper::_html(timeago($comment->created_at)); ?></span>
  </span>
  <a class="delete-comment only-user only-user-<?php echo Jade\Dumper::_text($comment->officer->user->id); ?>" href="<?php echo Jade\Dumper::_text(route('comment_destroy', array($comment->project->id, $comment->id))); ?>">Delete</a>
</div>