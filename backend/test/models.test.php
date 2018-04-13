<?php

require 'models.php';


(function () {/* test_create_user */ 
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
  /**/
})();

print "\n\033[32m=== all tests pass == \033[0m\n";
