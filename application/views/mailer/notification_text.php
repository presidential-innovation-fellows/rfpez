<?php $parsed = $notification->parsed(); ?>

<?= $parsed["subject"] ?>

View your notifications on RFP-EZ: <?= route('notifications') ?>

<?= __('r.email_signature_text') ?>