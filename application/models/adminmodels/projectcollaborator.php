<?php namespace AdminModels;

class ProjectCollaborator extends \Eloquent {

  public static $table = "project_collaborators";

  public $columns = array(
    'id',
    'officer_id',
    'project_id',
    'created_at',
    'updated_at',
    'owner'
  );

  public $edit = array(
    'id',
    'officer_id',
    'project_id',
    'created_at',
    'updated_at',
    'owner'
  );

}