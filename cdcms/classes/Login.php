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
		header('Content-Type: application/json');
		echo json_encode(array('status' => 'error', 'message' => 'Access Denied'));
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
			$_SESSION['login_id'] = $res['id'];
			$_SESSION['login_firstname'] = $res['firstname'];
			$_SESSION['login_lastname'] = $res['lastname'];
			$_SESSION['login_email'] = $res['email'];
			$_SESSION['login_contact'] = $res['contact'];
			$_SESSION['login_address'] = $res['address'];
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
		$password = md5($password);
		$stmt = $this->conn->prepare("SELECT * FROM parent_list where email = ? and password = ? ");
		$stmt->bind_param("ss", $email, $password);
		$stmt->execute();
		$result = $stmt->get_result();
		if($result->num_rows > 0){
			$data = $result->fetch_array();
			if($data['status'] == 1){
				// Set all necessary session variables
				$_SESSION['login_id'] = $data['id'];
				$_SESSION['login_firstname'] = $data['firstname'];
				$_SESSION['login_lastname'] = $data['lastname'];
				$_SESSION['login_email'] = $data['email'];
				$_SESSION['login_contact'] = $data['contact'];
				$_SESSION['login_address'] = $data['address'];
				$_SESSION['login_type'] = 3; // 3 for parent
				$_SESSION['parent_login'] = true;

				return json_encode(array(
					'status' => 'success',
					'redirect' => 'parent_dashboard.php'
				));
			}else{
				return json_encode(array('status'=>'failed','msg'=>'Your Account has been blocked.'));
			}
		}else{
			return json_encode(array('status'=>'failed','msg'=>'Invalid email or password.'));
		}
	}
	public function register_parent(){
		// Set proper content type header
		header('Content-Type: application/json');
		
		try {
			extract($_POST);
			
			// Validate required fields
			if(empty($firstname) || empty($lastname) || empty($email) || empty($password) || empty($contact)){
				echo json_encode([
					'status' => 'failed',
					'msg' => 'All fields are required.'
				]);
				return;
			}
			
			// Check if email already exists
			$check = $this->conn->prepare("SELECT * FROM parent_list where email = ?");
			$check->bind_param("s", $email);
			$check->execute();
			if($check->get_result()->num_rows > 0){
				echo json_encode([
					'status' => 'failed',
					'msg' => 'Email already exists.'
				]);
				return;
			}
			
			// Hash password
			$password = md5($password);
			
			// Insert new parent
			$stmt = $this->conn->prepare("INSERT INTO parent_list (firstname, middlename, lastname, email, password, contact, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("sssssss", $firstname, $middlename, $lastname, $email, $password, $contact, $address);
			
			if($stmt->execute()){
				$_SESSION['parent_login'] = true;
				$_SESSION['parent_id'] = $this->conn->insert_id;
				$_SESSION['parent_name'] = $firstname . ' ' . $lastname;
				$_SESSION['parent_email'] = $email;
				
				echo json_encode([
					'status' => 'success',
					'msg' => 'Registration successful'
				]);
			}else{
				echo json_encode([
					'status' => 'failed',
					'msg' => 'An error occurred while saving the data.'
				]);
			}
		} catch (Exception $e) {
			error_log("Registration Error: " . $e->getMessage()); // Add logging
			echo json_encode([
				'status' => 'failed',
				'msg' => 'Server error: ' . $e->getMessage()
			]);
		}
		exit;
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
	case 'register_parent':
		echo $auth->register_parent();
		break;
	default:
		echo $auth->index();
		break;
}

