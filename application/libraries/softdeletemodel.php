<?php

class SoftDeleteModel extends Laravel\Database\Eloquent\Model {
  protected function query() {
    return new SoftDeleteQuery($this);
  }

  public function delete() {
    if ($this->exists) {
      $this->fire_event('deleting');

      $result = $this->query()->where(static::$key, '=', $this->get_key())->update(array('deleted_at' => time()));

      $this->fire_event('deleted');

      return $result;
    }
  }
}