<?php
require_once __DIR__ . '/../core/init.php'; 

$user = DB::getInstance()->insert('users', array(
    'username' => 'Dale',
    'password' => 'password',
    'salt' => 'salt'

));
