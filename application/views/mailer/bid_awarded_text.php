Your bid on <?= $bid->project->title ?> has won <?= route('bid', array($bid->project->id, $bid->id)) ?>

"<?= $bid["awarded_message"] ?>"

<?= __('r.email_signature_text') ?>