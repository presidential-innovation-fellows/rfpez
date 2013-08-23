<div class="subheader">
  <?php Section::inject('page_title', "$project->title") ?>
  <?php if ($project->is_mine()): ?>
    <?php Section::inject('no_page_header', true) ?>
    <?php echo View::make('projects.partials.toolbar')->with('project', $project); ?>
    <?php echo View::make('projects.partials.answer_question_form'); ?>
  <?php else: ?>
    <div class="subheader-secondline"><?php echo $project->agency; ?></div>
  <?php endif; ?>
</div><!-- subheader -->

<div class="container inner-container inner-container-show-project">
  <?php Section::inject('active_subnav', "view") ?>
  <div class="row">
    <div class="span6">

      <?php echo View::make('projects.partials.full_sow')->with('project', $project); ?>
    </div>

    <div class="span5 offset1">

      <?php if ($project->source != Project::SOURCE_NATIVE): ?>

        <?php if (Auth::vendor()): ?>
          <?php echo View::make('projects.partials.external_url_link_button')->with('project', $project); ?>
        <?php else: ?>
          <div class="no-auth-only">
            <a class="btn btn-success" href="#signinModal" data-toggle="modal">Sign in</a> to bid or learn more about this project.
          </div>
        <?php endif; ?>

        <p>&nbsp;</p>

        <div class="share">
          <h5>Share</h5>
          <p>
            <div class="addthis_toolbox addthis_default_style ">
            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
            <a class="addthis_button_tweet"></a>
            <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript">
              // var addthis_share = {"title": "#{}"};
            </script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50b67b147c93387b"></script>
          </p>
        </div>

      <?php else: ?>

        <div class="well-plain">
          <div class="bids-due-timeago">Bids due <?php echo Helper::timeago($project->proposals_due_at); ?></div>
          <div class="bids-due-date"><?php echo date('F jS, Y', strtotime($project->proposals_due_at)); ?></div>
        </div>

        <?php if (Auth::vendor()): ?>
          <?php if ($bid = $project->my_current_bid()): ?>
            <a class="btn btn-action-gold" href="<?php echo e(route('bid', array($project->id, $bid->id))); ?>">View my bid</a>
          <?php elseif ($bid = $project->my_current_bid_draft()): ?>
            <a class="btn btn-large btn-success btn-extra-large" href="<?php echo e(route('new_bids', array($project->id))); ?>">Continue Writing Bid</a>
          <?php else: ?>
            <a class="btn btn-large btn-success btn-extra-large" href="<?php echo e(route('new_bids', array($project->id))); ?>">Bid on this Project</a>
          <?php endif; ?>
        <?php endif; ?>

        <div class="no-auth-only">
          <a class="btn btn-success" href="#signinModal" data-toggle="modal">Sign in</a> to bid on this project.
        </div>

        <hr />

        <p>&nbsp;</p>

        <div class="share">
          <h5>Share</h5>
          <p>
            <div class="addthis_toolbox addthis_default_style ">
            <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
            <a class="addthis_button_tweet"></a>
            <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript">
              // var addthis_share = {"title": "#{}"};
            </script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50b67b147c93387b"></script>
          </p>
        </div>
        <hr />

        <div class="q-and-a">
          <h5>Q &amp; A</h5>
          <p><em>These questions and answers are public. To ask a question privately, <a href="mailto:<?php echo $project->owner_email(); ?>">email the project owner</a>.</em></p>
          <div class="questions">
            <?php if ($project->questions): ?>
              <?php foreach($project->questions as $question): ?>
                <?php echo View::make('projects.partials.question')->with('question', $question); ?>
              <?php endforeach; ?>
            <?php else: ?>
              <p class="no-questions-asked"><?php echo __("r.projects.show.no_questions"); ?></p>
            <?php endif; ?>
          </div>
          <div class="vendor-only">
            <?php if ($project->question_period_is_open()): ?>
              <h4>Ask a question about this project</h4>
              <form id="ask-question-form" action="<?php echo e(route('questions')); ?>" method="post">
                <input type="hidden" name="project_id" value="<?php echo e($project->id); ?>" />
                <textarea name="question" placeholder="Type your question here"></textarea>
                <button class="btn btn-primary" data-loading-text="Sending...">Submit Question</button>
              </form>
            <?php else: ?>
              <p>Sorry, the Q+A period is over and no new questions can be asked.</p>
            <?php endif; ?>
          </div>
        </div>

      </div>
    <?php endif; // rpfez native project ?>

  </div>
</div>