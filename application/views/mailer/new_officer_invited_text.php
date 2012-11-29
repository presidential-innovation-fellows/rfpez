You've been invited to collaborate on a project, "<?= $project->title ?>".

Click here to sign up for your account: <?= route('finish_signup', array($new_user->reset_password_token)) ?>

<?= __('r.email_signature_text') ?>