</php
require_once '../core/init.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.0.0/mdb.min.css">
</head>
</html>
<form action = "" method ="post">
<div class = "field">
    <label for="username">Username</label>
    <input type = "text" name = "username"  id ="username" value = "" autocomplete = "off">
</div>

<div class="field">
    <label for ="password">Choose Password</label>
    <input type="password"name="password" id ="password">
</div>

<div class="field">
    <label for ="password_again">Enter your pasword again</label>
    <input type ="password" name ="password_again" id="password_again">
</div>

<div class="field">
    <label for ="name">Enter your pasword again</label>
    <input type ="text" name ="name" value="" id="name">
</div>

<input type ="submit" value ="Register">



</form>
