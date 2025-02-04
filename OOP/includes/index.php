<?php
require_once __DIR__ . '/../core/init.php'; // Absolute path for testing

$db = DB::getInstance();
$results = $db->query("SELECT * FROM users WHERE username = 'alex'")->results(); // Direct query

var_dump($results); // Check the results

if (count($results) > 0) {
    echo "User found!";
} else {
    foreach($user->results() as $user) {
        echo $user->$username, '<br>';
    }
    
}