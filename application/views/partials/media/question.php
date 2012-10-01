<div class="question-wrapper" data-id="<?= $question->id ?>">
  <div class="question well"><?= $question->question ?></div>
  <?php if ($question->answer): ?>
    <div class="answer"><?= $question->answer ?></div>
  <?php else: ?>
    <div class="answer-question only-user only-user-<?= $question->contract->officer->id ?>">
      <a class="answer-question-toggle">Answer Question</a>
    </div>
  <?php endif; ?>
</div>
