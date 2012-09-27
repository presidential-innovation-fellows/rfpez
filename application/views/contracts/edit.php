<?php Section::inject('page_title', 'Edit Contract'); ?>

<h3>Edit Contract</h3>

<form action="<?= route('contract', array($contract->id)) ?>" method="POST">
  <input type="hidden" name="_method" value="PUT" />

  <label>Sol Nbr of FBO Contract:</label>
  <input type="text" readonly value="<?= $contract->fbo_solnbr ?>" />

  <label>Agency</label>
  <input type="text" name="contract[agency]" value="<?= $contract->agency ?>" />

  <label>Office</label>
  <input type="text" name="contract[office]" value="<?= $contract->office ?>" />

  <label>Set Aside</label>
  <input type="text" name="contract[set_aside]" value="<?= $contract->set_aside ?>" />

  <label>Classification Code</label>
  <input type="text" name="contract[classification_code]" value="<?= $contract->classification_code ?>" />

  <label>Naics Code</label>
  <input type="text" name="contract[naics_code]" value="<?= $contract->naics_code ?>" />

  <label>Proposals Due At</label>
  <input type="text" name="contract[proposals_due_at]" value="<?= $contract->proposals_due_at ?>" />

  <label>Posted At</label>
  <input type="text" name="contract[posted_at]" value="<?= $contract->posted_at ?>" />

  <label>Statement of Work</label>
  <textarea name="contract[statement_of_work]"><?= $contract->statement_of_work ?></textarea>

  <br /><br />

  <input type="submit" />

</form>
