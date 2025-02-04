<?php
require_once  '../core/init.php'; 

$user = DB::getInstance()->update('users', 7, array(
     'username' => 'Leo',
     'password' => 'newpassword',
     'name' => 'Leo Gem'
     
   ));

if ($user) {
    echo "Update successful!";
} else {
    echo "Update failed.";
}