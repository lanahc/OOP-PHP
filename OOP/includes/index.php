<?php
require_once '../core/init.php';

$users = DB::getInstance()->query('SELECT username FROM users');
if($users->count()) {
    foreach($users as $user) {
        echo $user->username;
    }
}