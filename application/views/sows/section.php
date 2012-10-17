<?php Section::inject('page_title', $sow->title) ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('active_sidebar', 'section-' . $section_type) ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $sow->project)); ?>
<form class="form-horizontal form-sections" method="POST">
  <div class="row">
    <div class="span3">
      <?php echo Jade\Dumper::_html(View::make('sows.partials.sidebar')->with('project', $sow->project)); ?>
    </div>
    <div class="span9 step step-3">
      <?php if ($help_text = $sow->template->get_variable($section_type)): ?>
        <div class="alert alert-info"><?php echo Jade\Dumper::_text($help_text); ?></div>
      <?php endif; ?>
      <?php $today = date('n/j/y') ?>
      <?php foreach($sections as $section): ?>
        <div class="control-group">
          <div class="controls">
            <label class="checkbox">
              <input type="checkbox" value="<?php echo Jade\Dumper::_text($section->id); ?>" name="sections[]" class="section-toggle" <?php echo Jade\Dumper::_text($section->in_sow($sow) ? "checked" : ""); ?>>
              <span class="checkbox-tooltip" title="<?php echo Jade\Dumper::_text($section->help_text); ?>"><?php echo Jade\Dumper::_text($section->title); ?></span>
            </label>
            <?php if ($section_type == "Deliverables"): ?>
              <span class="input-append date datepicker" data-date="<?php echo Jade\Dumper::_text($today); ?>" data-date-format="m/d/yy">
                Due Date:
                <input size="16" type="text" value="<?php echo Jade\Dumper::_text($section->in_sow($sow) ? $sow->due_date($section) : $today); ?>" name="deliverable_dates[]" disabled="disabled" />
                <span class="add-on">
                  <i class="icon-calendar"></i>
                </span>
              </span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
      <ul class="custom-choices">
        <li class="template clearfix">
          <div class="choice-name">
            <span>&nbsp;</span>
            <input type="hidden" disabled="disabled" name="custom_sections[]" />
            <input type="hidden" disabled="disabled" name="custom_section_bodies[]" />
            <a>remove</a>
          </div>
          <?php if ($section_type == "Deliverables"): ?>
            <span class="input-append date datepicker" data-date="<?php echo Jade\Dumper::_text($today); ?>" data-date-format="m/d/yy">
              Due Date:
              <input size="16" type="text" value="<?php echo Jade\Dumper::_text($today); ?>" disabled="disabled" name="custom_deliverable_dates[]" />
              <span class="add-on">
                <i class="icon-calendar"></i>
              </span>
            </span>
          <?php endif; ?>
        </li>
        <?php foreach($custom_sections as $custom_section): ?>
          <li class="clearfix">
            <div class="choice-name">
              <span><?php echo Jade\Dumper::_text($custom_section->title); ?></span>
              <input type="hidden" name="custom_sections[]" value="<?php echo Jade\Dumper::_text($custom_section->title); ?>" />
              <input type="hidden" name="custom_section_bodies[]" value="<?php echo Jade\Dumper::_text($custom_section->body); ?>" />
              <a>(remove)</a>
            </div>
            <?php if ($section_type == "Deliverables"): ?>
              <span class="input-append date datepicker" data-date="<?php echo Jade\Dumper::_text($sow->due_date($custom_section)); ?>" data-date-format="m/d/yy">
                Due Date:
                <input size="16" type="text" value="<?php echo Jade\Dumper::_text($sow->due_date($custom_section)); ?>" name="custom_deliverable_dates[]" />
                <span class="add-on">
                  <i class="icon-calendar"></i>
                </span>
              </span>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
        <div class="control-group">
          <div class="controls">
            <input id="add-custom-choice-text" type="text" placeholder="Other" />
            <button id="add-custom-choice-btn" class="btn btn-success">Add</button>
          </div>
        </div>
      </ul>
      <div class="bottom-controls well">
        <?php if ($previous_template_section_type = $sow->template_section_type_before($section_type)): ?>
          <a class="btn" href="<?php echo Jade\Dumper::_text(route('sow_section', array($sow->project->id, $previous_template_section_type))); ?>">
            &larr; <?php echo Jade\Dumper::_text($previous_template_section_type); ?>
          </a>
        <?php else: ?>
          <a class="btn" href="<?php echo Jade\Dumper::_text(route('sow_background', array($sow->project->id))); ?>">
            &larr; Background &amp; Scope
          </a>
        <?php endif; ?>
        <button class="btn btn-primary pull-right">
          <?php if ($next_template_section_type = $sow->template_section_type_after($section_type)): ?>
            <?php echo Jade\Dumper::_text($next_template_section_type); ?>
          <?php else: ?>
            Fill in Variables
          <?php endif; ?>
          &rarr;
        </button>
      </div>
    </div>
  </div>
</form>