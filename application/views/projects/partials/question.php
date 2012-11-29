<div class="question-wrapper well" data-question-id="<?php echo $question->id; ?>">
  <div class="question">Q: <?php echo $question->question; ?></div>
  <div class="answer">
    <?php if ($question->answer): ?>
      A: <?php echo $question->answer; ?>
      <div class="answerer">
        <em>Answered by <?php echo $question->answerer->user->email; ?></em>
      </div>
    <?php else: ?>
      <em><?php echo __("r.projects.partials.question.not_answered"); ?></em>
      <?php if (Auth::officer() && Auth::officer()->collaborates_on($question->project->id)): ?>
        <div class="answer-question">
          <a class="answer-question-toggle">Answer Question</a>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>