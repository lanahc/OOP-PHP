<?php
require_once('DBConnection.php');

class ParentPortal extends DBConnection {
    private $settings;
    
    public function __construct(){
        global $_settings;
        $this->settings = $_settings;
        parent::__construct();
    }

    public function __destruct(){
        parent::__destruct();
    }

    public function save_child(){
        extract($_POST);
        
        if(empty($id)){
            $sql = "INSERT INTO `children_list` (`parent_id`, `firstname`, `middlename`, `lastname`, `gender`, `dob`, `age_group`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("issssss",
                $_SESSION['login_id'],
                $firstname,
                $middlename,
                $lastname,
                $gender,
                $dob,
                $age_group
            );
        } else {
            $sql = "UPDATE `children_list` SET 
                    `firstname` = ?, `middlename` = ?, `lastname` = ?,
                    `gender` = ?, `dob` = ?, `age_group` = ?
                    WHERE `id` = ? AND `parent_id` = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssssssii",
                $firstname,
                $middlename,
                $lastname,
                $gender,
                $dob,
                $age_group,
                $id,
                $_SESSION['login_id']
            );
        }

        $save = $stmt->execute();
        if($save){
            $resp['status'] = 'success';
            $resp['msg'] = 'Child information successfully saved.';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occurred.';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }

    public function get_child(){
        extract($_POST);
        $sql = "SELECT * FROM `children_list` WHERE id = ? AND parent_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $id, $_SESSION['login_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $resp['status'] = 'success';
        $resp['data'] = $result->fetch_assoc();
        return json_encode($resp);
    }

    public function update_profile(){
        extract($_POST);
        
        if(!empty($new_password)){
            if(md5($current_password) != $_SESSION['login_password']){
                $resp['status'] = 'failed';
                $resp['msg'] = 'Current password is incorrect.';
                return json_encode($resp);
            }
            if($new_password != $confirm_password){
                $resp['status'] = 'failed';
                $resp['msg'] = 'New passwords do not match.';
                return json_encode($resp);
            }
            $password = md5($new_password);
        }

        $sql = "UPDATE `parent_list` SET 
                `firstname` = ?, `lastname` = ?, 
                `contact` = ?, `address` = ? 
                ".(!empty($password) ? ", `password` = '" . $password . "'" : ""). "
                WHERE `id` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi",
            $firstname,
            $lastname,
            $contact,
            $address,
            $_SESSION['login_id']
        );

        $save = $stmt->execute();
        if($save){
            foreach(['firstname', 'lastname', 'contact', 'address'] as $k){
                if(isset($$k))
                    $_SESSION['login_'.$k] = $$k;
            }
            if(!empty($password))
                $_SESSION['login_password'] = $password;
            
            $resp['status'] = 'success';
            $resp['msg'] = 'Profile successfully updated.';
        } else {
            $resp['status'] = 'failed';
            $resp['msg'] = 'An error occurred.';
            $resp['error'] = $this->conn->error;
        }
        return json_encode($resp);
    }
}