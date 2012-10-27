<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Sections") ?>
<?php Section::inject('active_subnav', 'view') ?>
<?php Section::inject('no_page_header', true) ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<h4>Sections</h4>