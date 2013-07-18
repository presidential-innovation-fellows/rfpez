<div class="subheader">
  <?php Section::inject('page_title', "$project->title") ?>
  <?php Section::inject('page_action', "Comments") ?>
  <?php Section::inject('active_subnav', 'comments') ?>
  <?php Section::inject('no_page_header', true) ?>
  <?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
</div>
<div class="container inner-container">
  <div class="comments-list">
    <script type="text/javascript">
      $(function(){
       new Rfpez.Backbone.Comments( <?php echo $project->id; ?>, <?php echo $comments; ?> )
      })
    </script>
  </div>
  <div class="form-actions">
    <h5>Add Comment</h5>
    <form id="add-comment-form" action="<?php echo e('comments'); ?>" method="POST" data-officer-name="<?php echo e(Auth::officer()->name); ?>" data-officer-user-id="<?php echo e(Auth::officer()->user_id); ?>">
      <textarea class="span5" name="body"></textarea>
      <div>
        <button class="btn btn-primary">Submit Comment</button>
      </div>
    </form>
  </div>
</div>