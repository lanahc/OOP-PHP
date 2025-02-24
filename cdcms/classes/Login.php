<?php
require_once '../config.php';
class Login extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;

		parent::__construct();
		ini_set('display_error', 1);
	}
	public function __destruct(){
		parent::__destruct();
	}
	public function index(){
		echo "<h1>Access Denied</h1> <a href='".base_url."'>Go Back.</a>";
	}
	public function login(){
		extract($_POST);

		$qry = $this->conn->query("SELECT * from users where username = '$username' and password = md5('$password')");
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] != 1){
				return json_encode(array('status'=>'notverified'));
			}
			foreach($res as $k => $v){
				if(!is_numeric($k) && $k != 'password'){
					$this->settings->set_userdata($k,$v);
				}
			}
			$this->settings->set_userdata('login_type',1);
		return json_encode(array('status'=>'success'));
		}else{
		return json_encode(array('status'=>'incorrect','last_qry'=>"SELECT * from users where username = '$username' and password = md5('$password') "));
		}
	}
	public function logout(){
		if($this->settings->sess_des()){
			redirect('admin/login.php');
		}
	}
	function student_login(){
		extract($_POST);
		$qry = $this->conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as fullname from student_list where email = '$email' and `password` = md5('$password') ");
		if($this->conn->error){
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred while fetching data. Error:". $this->conn->error;
		}else{
		if($qry->num_rows > 0){
			$res = $qry->fetch_array();
			if($res['status'] == 1){
				foreach($res as $k => $v){
					$this->settings->set_userdata($k,$v);
				}
				$this->settings->set_userdata('login_type',2);
				$resp['status'] = 'success';
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "Your Account is not verified yet.";
			}
			
		}else{
		$resp['status'] = 'failed';
		$resp['msg'] = "Invalid email or password.";
		}
		}
		return json_encode($resp);
	}
	public function student_logout(){
		if($this->settings->sess_des()){
			redirect('./');
		}
	}
	public function login_parent() {
		extract($_POST);
		
		$sql = "SELECT * FROM parent_list WHERE email = ?";
		$stmt = $this->conn->prepare($sql);
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();

		if($result->num_rows > 0) {
			$data = $result->fetch_assoc();
			if(password_verify($password, $data['password'])) {
				if($data['status'] == 1) {
					foreach($data as $k => $v) {
						if(!is_numeric($k) && $k != 'password') {
							$_SESSION['login_'.$k] = $v;
						}
					}
					$_SESSION['login_type'] = 'parent';
					
					$resp['status'] = 'success';
					if(isset($redirect) && !empty($redirect)) {
						$resp['redirect'] = $redirect;
					}
				} else {
					$resp['status'] = 'failed';
					$resp['msg'] = 'Your account has been deactivated. Please contact the administrator.';
				}
			} else {
				$resp['status'] = 'failed';
				$resp['msg'] = 'Incorrect password.';
			}
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = 'Email not found.';
		}
		return json_encode($resp);
	}
}
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
	case 'login':
		echo $auth->login();
		break;
	case 'logout':
		echo $auth->logout();
		break;
	case 'student_login':
		echo $auth->student_login();
		break;
	case 'student_logout':
		echo $auth->student_logout();
		break;
	case 'login_parent':
		echo $auth->login_parent();
		break;
	default:
		echo $auth->index();
		break;
}

