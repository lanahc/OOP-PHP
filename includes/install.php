<?php
require_once '../core/init.php';
require_once '../classes/DB.php';

// Insert initial admin user (execute only once)
$password = password_hash('admin123', PASSWORD_DEFAULT); 
$db = DB::getInstance();
$db->insert('admins', array(
    'username' => 'admin',
    'password' => $password
));

?>