<div class="modal hide template-preview-modal" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button class="close" type="button" data-dismiss="modal">&times;</button>
    <h3><?php echo Jade\Dumper::_text($template->title); ?></h3>
  </div>
  <div class="modal-body">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.full_sow')->with('project', $template)); ?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">Close</button>
  </div>
</div>