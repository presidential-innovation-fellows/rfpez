<p>
  You've been invited to collaborate on a project, <strong>"<?= $project->title ?>"</strong>.
</p>

<p>
  Click here to sign up for your account: <a href="<?= route('finish_signup', array($new_user->reset_password_token)) ?>"><?= route('finish_signup', array($new_user->reset_password_token)) ?></a>
</p>

<?= __('r.email_signature_html') ?>