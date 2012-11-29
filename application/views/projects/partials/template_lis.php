<?php foreach($templates as $template): ?>
  <li class="template well">
    <div class="row-fluid">
      <div class="span6">
        <div class="title"><?php echo $template->title; ?></div>
        <div class="author"><?php echo $template->owner()->name; ?></div>
      </div>
      <div class="span3">
        <div class="forked">
          Forked <a href="#"><?php echo $template->fork_count; ?> <?php echo Str::plural('time', $template->fork_count); ?></a>
        </div>
        <?php if ($template->recommended): ?>
          <div class="recommended">&#9733; Recommended Template</div>
        <?php endif; ?>
      </div>
      <div class="span3">
        <a class="btn btn-info preview-button">Preview</a>
        <a class="btn btn-success" href="<?php echo route('project_template_post', array($project->id, $template->id)); ?>">Fork
</a>
        <?php echo View::make('projects.partials.template_preview_modal')->with('template', $template); ?>
      </div>
    </div>
  </li>
<?php endforeach; ?>