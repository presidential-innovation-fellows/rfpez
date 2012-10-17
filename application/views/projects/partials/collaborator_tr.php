<tr data-officer-id="<?php echo Jade\Dumper::_text($officer->id); ?>">
  <td class="email"><?php echo Jade\Dumper::_text($officer->user->email); ?></td>
  <td>
    <?php if ($officer->id == $project->owner()->id) { ?>
      <i class="icon-star"></i>
    <?php } ?>
  </td>
  <td>
    <button class="btn btn-danger remove-collaborator-button only-user only-user-<?php echo Jade\Dumper::_text($project->owner()->user->id); ?> not-user-<?php echo Jade\Dumper::_text($officer->user->id); ?>" href="<?php echo Jade\Dumper::_text(route('project_collaborators_destroy', array($project->id, $officer->id))); ?>">Remove</button>
    <span class="only-user only-user-<?php echo Jade\Dumper::_text($officer->user->id); ?>">This is you!</span>
  </td>
</tr>