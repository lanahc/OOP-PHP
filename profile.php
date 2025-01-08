<?php
require "finalauto.php";
$ObjGlob->sign_in_form($conn, $ObjGlob, $ObjSendMail) ;
$ObjForm->profile_form($ObjGlob, $conn);
$ObjCont->side_bar();
