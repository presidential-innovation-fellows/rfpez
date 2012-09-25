<div class="nav-collapse collapse">
  <ul class="nav">
        <?php if (Auth::check()): ?>

          <?php if (Auth::user()->is_officer()): ?>
            <li><a href="<?= route('new_contracts') ?>">new contract</a></li>
            <li><a href="<?= route('my_contracts') ?>">my contracts</a></li>
          <?php else: ?>
            <li><a href="<?= route('contracts') ?>">browse contracts</a></li>
          <?php endif; ?>

          <li class="pull-right"><a href="<?= route('signout') ?>">sign out</a></li>

        <?php else: ?>
          <li><a href="<?= route('signin') ?>">sign in</a></li>
          <li><a href="<?= route('new_vendors') ?>">new vendor</a></li>
          <li><a href="<?= route('new_officers') ?>">new officer</a></li>
        <?php endif; ?>
  </ul>
</div>