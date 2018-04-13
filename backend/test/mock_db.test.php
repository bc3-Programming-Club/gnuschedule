<?php

require 'mock_db.php';

class TestTable extends Table {
  public $data = NULL;
}


(/* test_table_create_row */ function () {
  TestTable::drop();

  $row = new TestTable(['data' => 'a']);
  assert( is_a($row, 'Table') );
  assert( $row->data == 'a' );
})();

(/* test_table_find */ function () {
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
  
})();


(/* test_table_find */ function () {
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

})();

(/* test_create_user */ function () {
  User::drop();

  $email = 'darius@email.com';
  $pwd = password_hash('a', PASSWORD_DEFAULT);
  $user = new User([
    'email' => $email,
    'password_hash' => $pwd
  ]);

  assert( $user->email == $email );
  assert( $user->password_hash == $pwd );
  assert( $user->is_admin === FALSE );
})();

print "\n\033[32m=== all tests pass == \033[0m\n";
