<?php Section::inject('page_title', $sow->title) ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('active_sidebar', 'background') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $sow->project)); ?>
<form method="POST">
  <div class="row">
    <div class="span3">
      <?php echo Jade\Dumper::_html(View::make('sows.partials.sidebar')->with('project', $sow->project)); ?>
    </div>
    <div class="span9 step step-2">
      <div class="alert alert-info">
        First, let's compose a background and scope for your SOW. Tell us about your organization,
        and the problem you're trying to solve with this SOW.
      </div>
      <h4 class="sidebar-section-title">Writing A Great SOW</h4>
      <ul>
        <li>The background should identify the work in very general terms</li>
        <li>Describe your organization and why you're pursuing these goals</li>
        <li>Now is the time to mention any regulations or laws affecting the job.</li>
        <li>2-5 Paragraphs in total</li>
        <li>Write so your neighbor can understand what you write.</li>
      </ul>
      <a class="examples-toggle" data-show-text="Show Examples [+]" data-hide-text="Hide Examples [-]">Show Examples [+]</a>
      <div class="examples well">
        <p>
          <em>Words that are {{TAGS}} can be filled in later.</em>
        </p>
        <strong>Scope</strong>
        <br />
        The {{AGENCY}}  {{OFFICE}} requires the services of a vendor that can provide a number of different products and/or services related to the strategic design, content creation, analysis and promotion of the overall rebranding of {{WEBSITE}}. Through the rebranding and creative development process, {{AGENCY}} hopes to conduct a comp lete audience analysis to drive external Department messaging, enhance user experience and modernize the {{AGENCY}} web presence to create a sustainable platform to promote {{AGENCY}}'s mission and emerge as a leader in government online communication.
        <br />
        <br />
        <strong>Project Background</strong>
        <br />
        The initial focus of this SOW will be on the analysis, strategy and creative development of the sites within the current {{WEBSITE}} branding and infrastructure to develop a sustainable platform and unique presence that complies with all the standards and regulations specific to public facing government websites and online communications. The {{AGENCY}} wishes to utilize the Simplified Acquisition Procedure, which limits the project's budget to $150,000.
      </div>
      <textarea id="sow-content-wysiwyg" name="body" style="width: 100%; min-height: 400px"><?php echo Jade\Dumper::_html($sow->background_and_scope()); ?></textarea>
      <div class="bottom-controls well">
        <a class="btn" href="/">&larr; Start Over</a>
        <button class="btn btn-primary pull-right"><?php echo Jade\Dumper::_text($sow->first_template_section_type()); ?> &rarr;</button>
      </div>
    </div>
  </div>
</form>