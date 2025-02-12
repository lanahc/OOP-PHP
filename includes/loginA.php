<?php

require_once '../core/init.php';
require_once '../classes/Admin.php';

if (isset($_POST['submit'])) {
    $username = Input::get('username'); 
    $password = Input::get('password'); 

    if (Admin::login($username, $password)) {
        Redirect::to('dashboardA.php');
    } else {
        echo "Invalid username or password."; 
    }
}
?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username">
    </div>

    <div class="field">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
    </div>

    <input type="submit" value="Login">
</form>