<?php namespace AdminModels;

class Project extends \Eloquent {

  public $columns = array(
    'id',
    'forked_from_project_id',
    'project_type_id',
    'title',
    'agency',
    'office',
    'fork_count',
    'recommended',
    'public',
    'background',
    'sections',
    'variables',
    'proposals_due_at',
    'sow_progress',
    'posted_to_fbo_at',
    'created_at',
    'updated_at'
  );

  public $edit = array(
    'id',
    'forked_from_project_id',
    'project_type_id',
    'title',
    'agency',
    'office',
    'fork_count',
    'recommended',
    'public',
    'background',
    'sections',
    'variables',
    'proposals_due_at',
    'sow_progress',
    'posted_to_fbo_at',
    'created_at',
    'updated_at'
  );

}