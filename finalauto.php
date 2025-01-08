<?php
session_start();
require "conn.php";

// Class Auto Load
function classAutoLoad($classname){

    $directories = ["contents", "forms", "processes", "glob"];

    foreach($directories AS $dir){
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR . $classname . ".php";
        if(file_exists($filename) AND is_readable($filename)){
            require_once $filename;
        }
    }
}

    spl_autoload_register('classAutoLoad');

    $ObjGlob = new functions();
    $ObjSendMail = new Mail();

// Create instances of all classes
    $ObjCont = new contents();
    $ObjForm = new users();
    $conn = new mysqli($servername, $username, $password, $dbname);

// Create process instances

    $ObjAuth = new auth();
    $ObjAuth->sign_up_form($conn, $ObjGlob, $ObjSendMail);
    $ObjAuth->verification_otp_form($conn, $ObjGlob, $ObjSendMail);
    $ObjAuth->sign_in_form($conn, $ObjGlob, $ObjSendMail);
    $ObjAuth->main_form($conn, $ObjGlob, $ObjSendMail);
    $ObjAuth->profile_form($conn, $ObjGlob);