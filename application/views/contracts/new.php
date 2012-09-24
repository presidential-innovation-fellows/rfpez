<?php Section::inject('page_title', 'New Contract'); ?>
<?php Section::start('content') ?>

<h3>New Contract</h3>

<form action="<?= route('contracts') ?>" method="POST">

  <label>Sol Nbr of FBO Contract:</label>
  <input type="text" name="solnbr" />

  <br /><br />

  <input type="submit" />

</form>

<?php Section::stop() ?>
