<?php
ob_start();
ini_set('date.timezone', 'Africa/Nairobi'); 
date_default_timezone_set('Africa/Nairobi'); 
session_start();

require_once('initialize.php');
require_once('classes/DBConnection.php');
require_once('classes/SystemSettings.php');
$db = new DBConnection;
$conn = $db->conn;

function redirect($url=''){
	if(!empty($url))
	echo '<script>location.href="'.base_url .$url.'"</script>';
}
function validate_image($file){
	if(!empty($file)){
			// exit;
        $ex = explode('?',$file);
        $file = $ex[0];
        $param =  isset($ex[1]) ? '?'.$ex[1]  : '';
		if(is_file(base_app.$file)){
			return base_url.$file.$param;
		}else{
			return base_url.'dist/img/no-image-available.png';
		}
	}else{
		return base_url.'dist/img/no-image-available.png';
	}
}
function isMobileDevice(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );

    //Return true if Mobile User Agent is detected
    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    //Otherwise return false..  
    return false;
}

// Add these functions for statistics if they don't exist
function getTotalStats($conn) {
    $stats = array();
    
    // Get total children
    $sql = "SELECT COUNT(*) as total FROM children WHERE delete_flag = 0";
    $query = $conn->query($sql);
    $stats['total_children'] = $query->fetch_assoc()['total'];
    
    // Get total babysitters
    $sql = "SELECT COUNT(*) as total FROM babysitters WHERE delete_flag = 0";
    $query = $conn->query($sql);
    $stats['total_babysitters'] = $query->fetch_assoc()['total'];
    
    // Get enrollment statistics
    $sql = "SELECT status, COUNT(*) as count FROM enrollments WHERE delete_flag = 0 GROUP BY status";
    $query = $conn->query($sql);
    $stats['enrollments'] = array();
    while($row = $query->fetch_assoc()) {
        $stats['enrollments'][$row['status']] = $row['count'];
    }
    
    return $stats;
}

// Add this to your existing SystemSettings class if needed
function getSystemInfo(){
    // ... existing code ...
    $stats = getTotalStats($this->conn);
    foreach($stats as $k => $v){
        $this->settings[$k] = $v;
    }
    return $this->settings;
}

ob_end_flush();
?>