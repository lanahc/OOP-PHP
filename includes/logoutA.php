<?php
require_once '../classes/Admin.php';

Admin::logout();

header("Location: loginA.php");
exit;
?>