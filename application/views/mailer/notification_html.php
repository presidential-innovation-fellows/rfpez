<?php $parsed = $notification->parsed(); ?>

<p>
  <strong><?= $parsed["line1"] ?></strong>
</p>

<p>
  <?= $parsed["line2"] ?>
</p>

<?= __('r.email_signature_html') ?>