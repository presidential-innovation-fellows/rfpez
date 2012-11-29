<p>
  <strong>Your bid on <a href="<?= route('bid', array($bid->project->id, $bid->id)) ?>"><?= $bid->project->title ?></a> has won</strong>
</p>

<p>
  "<?= $bid["awarded_message"] ?>"
</p>

<?= __('r.email_signature_html') ?>