<div class="question-wrapper well" data-question-id="<?php echo Jade\Dumper::_text($question->id); ?>">
  <div class="question">Q: <?php echo Jade\Dumper::_text($question->question); ?></div>
  <div class="answer">
    <?php if ($question->answer): ?>
      A: <?php echo Jade\Dumper::_text($question->answer); ?>
      <div class="answerer">
        <em>Answered by <?php echo Jade\Dumper::_text($question->answerer->user->email); ?></em>
      </div>
    <?php else: ?>
      <em>This question has not been answered.</em>
      <?php if (Auth::officer() && Auth::officer()->collaborates_on($question->project->id)): ?>
        <div class="answer-question">
          <a class="answer-question-toggle">Answer Question</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>