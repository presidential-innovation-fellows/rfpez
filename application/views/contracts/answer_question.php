<form action="<?= route('answer_question') ?>" method="post" id="answer-question-form">
  <input type="hidden" name="id" val="" />
  <textarea name="answer" placeholder="Answer"></textarea>
  <button class="btn btn-primary" data-loading-text="Submitting...">Submit</button>
</form>