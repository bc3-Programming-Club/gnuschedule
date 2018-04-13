<?php

require 'table.php';

class User extends Table {
    public $email;
    public $password_hash;
    public $is_admin = FALSE;
}

class Reservation extends Table {
    public $staff_email;
    public $client_email;
    public $begins_at;
    public $ends_at;
    public $comment;
}
