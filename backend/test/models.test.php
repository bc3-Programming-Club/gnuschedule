<?php

require '../models.php';

Model::$connection = new MemoryStorage();

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


// ====================================
// TEST CONFIRMATION CLASS
// verify that old confirmations expire and new ones don't
(function () {
	$conf = new Confirmation(['target' => 'test']);
	$conf->renew(2); // expires in 2 seconds
	
	sleep(1);
	assert($conf->expired() === FALSE); // should NOT expire after 1 second elapsed
	
	sleep(2);
	assert($conf->expired() === TRUE);  // SHOULD expire after 3 seconds elapsed
})();

// ====================================
// CREATE AND SEND CONFIRMATION CODE
// this would run after the user submits the registration form
(function () {
	$conf = Confirmation::findOneOrCreate(['target' => "user.email:darius@email.com"]);
	$GLOBALS['code'] = $conf->renew();
	// at this point, `use mail()` to send and email containing the code
})();

// ====================================
// CONFIRMATION COMPLETION
// this would run after the user clicks the link in the email OR enters the code
// by hand
(function () {
	$res = Confirmation::resolve($GLOBALS['code']);

	if ($res) {
		print "The confirmation was successful\n";
	} else {
		print "Sorry, the confirmation failed\n";
	}

	assert($res === Confirmation::of("user.email:darius@email.com"));
})();

print "\n\033[32m=== all tests pass == \033[0m\n";
