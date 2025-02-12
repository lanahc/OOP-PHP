<?php
require_once '../core/init.php';
require_once '../classes/Session.php';



if(Input::exists()) {
    if(Token::check(Input::get('token'))) {

    $validate = new Validate();
       $validation = $validate->check($_POST, array(
        'username' => array(
            'required' => true,
            'min' => 2,
            'max' => 20,
            'unique' => 'users'
        ),
        'password' => array(
            'required' => true,
            'min' => 6
        ),
        'password_again' => array(
            'required' => true,
            "matches" => 'password'
        ),
        'name' => array(
            'required' => true,
            'min' => 2,
            'max' => 50
            
        )
    ));

        if($validation->passed()) {
            $user = new User();
            try{

                $user->create(array(
                    'username' => '',
                    'password' => '',
                    'salt' => '',
                    'name' => '',
                    'joined' => '',
                    'group' => ''
                    
                    
                ));


            } catch(Exception $e) {
                die($e->getMessage());
            }
    } else {
       foreach($validation->errors() as $error){
        echo $error, '<br>';
       }
    }
}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <h1> Registration Form </h1>
    <link rel="stylesheet" href="../css/styles.css">
    
</head>
</html>
<form action = "" method ="post">
<div class = "field">
    <label for="username">Username</label>
    <input type = "text" name = "username"  id ="username" value = "<?php echo escape(Input::get('username')); ?>" autocomplete = "off">
</div>

<div class="field">
    <label for ="password">Choose Password</label>
    <input type="password"name="password" id ="password">
</div>

<div class="field">
    <label for ="password_again">Enter your password again</label>
    </label>
    <input type ="password" name ="password_again" id="password_again">
</div>

<div class="field">
    <label for ="name">Your name</label>
    <input type ="text" name ="name" value="<?php echo escape( Input::get('name')); ?>" id="name">
</div>

<input type="hidden" name="token" value="<?php echo Token::generate(); ?>"></br>
<input type="submit" value ="Register">



</form>
