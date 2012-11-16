<?php

class SoftDeleteQuery extends Laravel\Database\Eloquent\Query {

  public function __construct($model) {
    parent::__construct($model);
    $this->where_null('deleted_at');
  }

  public function deleted() {
    $wheres = $this->table->wheres;
    $wheres = array_filter($wheres, function($where){
      if(!($where['type'] == 'where_null' && $where['column'] == 'deleted_at'))
        return $where;
    });

    $this->table->wheres = $wheres;
    $this->where_not_null('deleted_at');

    return $this;
  }
}