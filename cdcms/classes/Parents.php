<?php
require_once('../config.php');

class Parents extends DBConnection {
    public function __construct(){
        parent::__construct();
    }

    public function save_parent(){
        extract($_POST);
        
        $data = "";
        foreach($_POST as $k => $v){
            if(!in_array($k, array('id'))){
                if(!empty($data)) $data .= ", ";
                $data .= "`$k`='$v'";
            }
        }

        if(empty($id)){
            $sql = "INSERT INTO parent_list SET $data";
        } else {
            $sql = "UPDATE parent_list SET $data WHERE id = $id";
        }

        $save = $this->conn->query($sql);
        if($save){
            return json_encode(array(
                'status' => 'success',
                'msg' => 'Parent successfully saved'
            ));
        } else {
            return json_encode(array(
                'status' => 'failed',
                'msg' => 'An error occurred. Error: '.$this->conn->error
            ));
        }
    }

    public function delete_parent(){
        extract($_POST);
        $delete = $this->conn->query("DELETE FROM parent_list WHERE id = $id");
        if($delete){
            return json_encode(array(
                'status' => 'success',
                'msg' => 'Parent successfully deleted'
            ));
        } else {
            return json_encode(array(
                'status' => 'failed',
                'msg' => 'An error occurred. Error: '.$this->conn->error
            ));
        }
    }

    public function get_parent(){
        extract($_POST);
        $qry = $this->conn->query("SELECT * FROM parent_list WHERE id = $id");
        $data = $qry->fetch_array();
        return json_encode($data);
    }
}

// Handle AJAX requests
if(isset($_GET['action'])){
    $crud = new Parents();
    switch($_GET['action']){
        case 'save':
            echo $crud->save_parent();
            break;
        case 'delete':
            echo $crud->delete_parent();
            break;
        case 'get_single':
            echo $crud->get_parent();
            break;
    }
}
?> 