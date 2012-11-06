Your bid on <?= $bid->project->title ?> has won <?= route('bid', array($bid->project->id, $bid->id)) ?>

"<?= $bid["awarded_message"] ?>"

-<?= Config::get('rfpez.email_signature_name') ?>