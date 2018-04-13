<?php

interface Storage {
    public function create_table($name);
    public function drop_table($name); 
    public function get_table($name);
    public function get_tables();

    public function insert($table, $row);
    public function remove($table, $index);
}

class MemoryStorage implements Storage {
    public $tables = [];

    function create_table($name) {
        $this->tables[$name] = [];
    }

    function has_table($name) {
        return in_array($name, array_keys($this->tables));
    }

    function get_table($name) {
        if ($this->has_table($name) === FALSE) {
            $this->create_table($name);
        }

        return $this->tables[$name];
    }

    function drop_table($name) {
        unset($this->tables[$name]);
    }
    
    function get_tables() {
        return $this->tables;
    }

    function insert($table, $row) {
        $this->tables[$table][]= $row;
    }

    function remove($table, $index) {
        unset($this->tables[$table][$index]);
    }
}

class Model {

    static $connection;

    function __construct(array $params=[]) {
        self::all(); // initialize the table
        self::$connection->insert(self::tablename(), $this);

        foreach ($params as $prop => $value) {
            $this->{$prop} = $value;
        }
    }

    static function tablename() {
        $Class = get_called_class();
        return "$Class";
    }

    static function all() {
        return self::$connection->get_table(self::tablename());
    }

    // find all rows with property values that match the specified parameters
    static function find(array $params) {
        return array_filter(self::all(), function ($row) use ($params) {

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
    
	static function findOneOrCreate(array $params) {
        $found = self::findOne($params);
        
        if ($found !== NULL) {
            return found;
        } else {
            $Class = get_called_class();
            return new $Class($params);
        }
    }
    static function drop() {
        self::$connection->drop_table(self::tablename());
    }
}

Model::$connection = new MemoryStorage();
