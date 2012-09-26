<?php Section::inject('page_title', $contract->title); ?>
<?php Section::start('content') ?>

<div class="row">
  <div class="span7">
    <h3><?= $contract->title ?></h3>

    <?= $contract->statement_of_work ?>
  </div>
  <div class="span5">
    <hr />
    <h4>Proposals due <?= RelativeTime::format($contract->proposals_due_at) ?></h4>
    <div class="vendor-only">
      <?php if (Auth::user() && Auth::user()->is_vendor() && $bid = $contract->current_bid_from(Auth::user()->vendor)): ?>
        <a href="<?= route('bid', array($contract->id, $bid->id)) ?>">View my bid</a>
      <?php else: ?>
        <a href="<?= route('new_bids', array($contract->id)) ?>">Bid on this Contract</a>
      <?php endif; ?>
    </div>
    <hr />
    <div class="q-and-a">
      <h4>Q &amp; A</h4>
      <div class="questions">
        <?php foreach($contract->questions as $question): ?>
          <div class="question-wrapper">
            <div class="question"><?= $question->question ?></div>
            <div class="answer"><?= $question->answer ?></div>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="ask-question vendor-only">
        <h4>Ask a question about this project</h4>
        <form action="<?= route('questions') ?>" id="ask-question-form" method="post">
          <input type="hidden" name="contract_id" value="<?= $contract->id ?>" />
          <textarea name="question" placeholder="Type your question here"></textarea><br />
          <button class="btn btn-primary" data-loading-text="Submitting...">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php Section::stop() ?>
