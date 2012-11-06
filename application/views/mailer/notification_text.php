<?php $parsed = $notification->parsed(); ?>

<?= $parsed["subject"] ?>

View your notifications on EasyBid: <?= route('notifications') ?>

-<?= Config::get('rfpez.email_signature_name') ?>