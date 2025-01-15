<?php
include 'user.php';

$users = new users();

$users->sign_up_form($conn, $ObjGlob, $ObjSendMail);

?>