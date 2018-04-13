<?php

function array_all(array $a) {
  return !in_array(FALSE, $a);
}

function array_map_assoc(array $a, callable $f) {
  return array_column(array_map($f, array_keys($a), $a), 1, 0);
}


class Table {

  static $rows = [];

  function __construct(array $params=[]) {
    self::$rows[]= $this;

    foreach ($params as $prop => $value) {
      $this->{$prop} = $value;
    }
  }

  // find all rows with property values that match the specified parameters
  static function find(array $params) {
    
    return array_filter(self::$rows, function ($row) use ($params) {
      /*$results = array_map_assoc($params, function($prop, $value) use ($row, $params) {
        return $row->{$prop} == $value;
      });*/

      $ok = TRUE;
      foreach ($params as $prop => $value) {
        if ($row->{$prop} != $value) {
          $ok = FALSE;
        }
      }

      return $ok; // return TRUE if all params matched
    });
  }

  static function findOne(array $params) {
    $found = self::find($params);

    if (count($found) > 0) {
      return current($found);
    } else {
      return NULL;
    }
  }

  static function drop() {
    self::$rows = [];
  }

  

}

class User extends Table {
  public $email;
  public $password_hash;
  public $is_admin = FALSE;
}
