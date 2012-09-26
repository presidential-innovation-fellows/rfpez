<div class="nav-collapse collapse">
  <?php if (Auth::check()): ?>
    <ul class="nav">
      <?php if (Auth::user()->is_officer()): ?>
        <li><a href="<?= route('new_contracts') ?>">new contract</a></li>
        <li><a href="<?= route('my_contracts') ?>">my contracts</a></li>
      <?php else: ?>
        <li><a href="<?= route('contracts') ?>">browse contracts</a></li>
      <?php endif; ?>
    </ul>
    <ul class="nav pull-right">
      <li><a href="<?= route('signout') ?>">sign out <?= Auth::user()->email ?></a></li>
    </ul>
  <?php else: ?>
    <ul class="nav">
      <li><a href="<?= route('new_vendors') ?>">new vendor</a></li>
      <li><a href="<?= route('new_officers') ?>">new officer</a></li>
    </ul>
    <ul class="nav pull-right">
      <li><a href="<?= route('signin') ?>">sign in</a></li>
    </ul>
  <?php endif; ?>
</div>