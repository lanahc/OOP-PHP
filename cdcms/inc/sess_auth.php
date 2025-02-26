<?php 
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') 
    $link = "https"; 
else
    $link = "http"; 
$link .= "://"; 
$link .= $_SERVER['HTTP_HOST']; 
$link .= $_SERVER['REQUEST_URI'];

// Public pages that don't require authentication
$public_pages = ['login.php', 'registration.php', 'parent_login.php', 'parent_registration.php'];

// Check if current page is in public pages
$is_public_page = false;
foreach($public_pages as $page) {
    if(strpos($link, $page) !== false) {
        $is_public_page = true;
        break;
    }
}

// Redirect to appropriate login page if not authenticated
if(!$is_public_page && 
   (!isset($_SESSION['userdata']) || 
    !isset($_SESSION['userdata']['login_type']))) {
    redirect('login.php');
}

// Redirect logged-in users away from login pages based on their type
if($is_public_page && isset($_SESSION['userdata']['login_type'])) {
    switch($_SESSION['userdata']['login_type']) {
        case 1: // Admin
            redirect('admin/index.php');
            break;
        case 2: // Student
            redirect('student/index.php');
            break;
        case 3: // Parent
            redirect('parent/index.php');
            break;
    }
}
