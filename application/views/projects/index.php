<?php Section::inject('page_title', (Auth::officer() ? 'Everybody\'s Projects' : 'Projects' )) ?>
<?php $yermom = false; ?>
<?php if ($yermom): ?>
<?php else: ?>
  <h3>No open projects. Check back soon.</h3>
<?php endif; ?>