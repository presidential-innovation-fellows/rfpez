<?php Section::inject('page_title', $contract->title); ?>
<?php Section::start('content') ?>

<h3><?= $contract->title ?></h3>

<?= $contract->statement_of_work ?>

<a href="<?= route('new_bids', array($contract->id)) ?>">Bid on this Contract</a>

<?php Section::stop() ?>
