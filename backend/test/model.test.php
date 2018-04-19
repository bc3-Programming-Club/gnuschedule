<?php

require '../model.php';

Model::$connection = new MemoryStorage();

class TestTable extends Model {
    public $data = NULL;
}

class TestTable2 extends Model {
    public $data = NULL;
}

(function () { /* test_table_create_row */
    TestTable::drop();

    $row = new TestTable(['data' => 'a']);
    assert( is_a($row, 'Model') );
    assert( $row->data == 'a' );
    /**/
})();

(function () { /* test_table_storage */

    $table = new Model();
    $ttable = new TestTable(['data' => 'lanthanum']);
    $ttable2 = new TestTable2(['data' => 'promethium']);

    //print var_dump($ttable2::all());
    
    //print "\n";
    //print var_dump([]);

    assert( count($table::all()) === 1 );
    assert( count($ttable::all()) === 1 );
    assert( count($ttable2::all()) === 1 );
    /**/
})();

(function () { /* test_table_find */
    TestTable::drop();

    // Create some rows

    $row1 = new TestTable(['data' => 'lithium']);

    $row2 = new TestTable(['data' => 'iron']);

    $row3 = new TestTable(['data' => 'lithium']);

    // Find by `data` property

    $results = TestTable::find(['data' => 'lithium']);

    assert( count($results) === 2 );
    assert( in_array($row1, $results) );
    assert( in_array($row3, $results) );

    // Find something that won't exist

    $results = TestTable::find(['data' => 'neon']);

    assert( count($results) === 0 );
    /**/  
})();


(function () { /* test_table_find */
    TestTable::drop();

    // Create some rows

    $row1 = new TestTable(['data' => 'lithium']);

    $row2 = new TestTable(['data' => 'iron']);

    $row3 = new TestTable(['data' => 'lithium']);

    // Find a single row

    $result = TestTable::findOne(['data' => 'lithium']);

    // If multiple rows fit the criteria, the first found is returned.

    assert( $result === $row1);

    // Attempt to find a row that does not exist

    $result = TestTable::findOne(['data' => 'dysprosium']);

    assert( $result === NULL );
    /**/  
})();

print "\n\033[32m=== all tests pass == \033[0m\n";
