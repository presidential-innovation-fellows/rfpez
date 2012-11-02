<?php Section::inject('page_title', "$project->title") ?>
<?php Section::inject('page_action', "Comments") ?>
<?php Section::inject('active_subnav', 'comments') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<?php Section::start('page_scripts'); { ?>
  <?php echo Jade\Dumper::_html(HTML::script('js/comments-backbone.js')); ?>
<?php } ?>
<?php Section::stop(); ?>
<div class="comments-list">
  <script type="text/javascript">
    $(function(){
     new Rfpez.Backbone.Comments( <?php echo Jade\Dumper::_text($project->id); ?>, <?php echo Jade\Dumper::_text($project->comments_json()); ?> )
    })
  </script>
</div>
<div class="form-actions">
  <h5>Add Comment</h5>
  <form id="add-comment-form" action="<?php echo Jade\Dumper::_text('comments'); ?>" method="POST" data-officer-name="<?php echo Jade\Dumper::_text(Auth::officer()->name); ?>" data-officer-user-id="Auth::officer()->user_id">
    <textarea class="span5" name="body"></textarea>
    <div>
      <button class="btn btn-primary">Submit Comment</button>
    </div>
  </form>
</div>