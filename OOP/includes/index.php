<?php
require_once __DIR__ . '/../core/init.php'; // Absolute path for testing

$user = DB::getInstance()->get('users', array('username', '=', 'billy'));

if($user->error()) {
    echo 'No user';
} else {
    echo 'OK!';
}