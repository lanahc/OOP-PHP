function save_enrollment(){
    try {
        extract($_POST);
        
        // Check if child is already enrolled (using child's full name and DOB)
        $child_fullname = "{$child_lastname}, {$child_firstname} {$child_middlename}";
        $check_existing = $this->conn->prepare("
            SELECT e.* FROM enrollment_list e 
            INNER JOIN enrollment_details ed1 ON e.id = ed1.enrollment_id AND ed1.meta_field = 'child_fullname' 
            INNER JOIN enrollment_details ed2 ON e.id = ed2.enrollment_id AND ed2.meta_field = 'child_dob'
            WHERE ed1.meta_value = ? AND ed2.meta_value = ? AND e.status = 1
        ");
        $check_existing->bind_param("ss", $child_fullname, $child_dob);
        $check_existing->execute();
        $result = $check_existing->get_result();

        if($result->num_rows > 0) {
            $resp['status'] = 'failed';
            $resp['msg'] = "This child is already enrolled. Each child can only be enrolled once.";
            return json_encode($resp);
        }

        // If no existing enrollment, proceed with new enrollment
        if(empty($_POST['id'])){
            $alpha = range("A","Z");
            shuffle($alpha);
            $prefix = (substr(implode("",$alpha),0,3))."-".(date('Ym'));
            $code = sprintf("%'.04d",1);
            while(true){
                $check = $this->conn->query("SELECT * FROM enrollment_list where `code` = '{$prefix}{$code}' ")->num_rows;
                if($check > 0){
                    $code = sprintf("%'.04d",ceil($code)+1);
                }else{
                    break;
                }
            }
            $_POST['code'] = "{$prefix}{$code}";
        }

        $_POST['child_fullname'] = $child_fullname;
        $_POST['parent_fullname'] = "{$_POST['parent_lastname']}, {$_POST['parent_firstname']} {$_POST['parent_middlename']}";
        
        // Continue with the rest of your existing save_enrollment code...
        $data = "";
        foreach($_POST as $k =>$v){
            if(in_array($k,array('code','child_fullname','parent_fullname','status'))){
                if(!is_numeric($v))
                    $v = $this->conn->real_escape_string($v);
                if(!empty($data)) $data .=",";
                $data .= " `{$k}`='{$v}' ";
            }
        }

        if(empty($id)){
            $sql = "INSERT INTO `enrollment_list` set {$data} ";
        }else{
            $sql = "UPDATE `enrollment_list` set {$data} where id = '{$id}' ";
        }
        
        $save = $this->conn->query($sql);
        if($save){
            $eid = !empty($id) ? $id : $this->conn->insert_id;
            $resp['status'] = 'success';
            if(empty($id)) {
                $resp['msg'] = "
                    <div class='text-center'>
                        <h4>Enrollment Details Successfully Submitted!</h4>
                        <p>Your Enrollment Code is: <b>{$code}</b></p>
                        <p>Please save this code as you'll need it for booking.</p>
                        <div class='mt-3'>
                            <a href='".base_url."book_babysitter.php' class='btn btn-primary'>Book a Babysitter Now</a>
                        </div>
                    </div>";
            } else {
                $resp['msg'] = "Enrollment details has been updated successfully.";
            }
            // ... rest of your existing code ...
        }

    } catch (Exception $e) {
        $resp['status'] = 'failed';
        $resp['msg'] = "An error occurred: " . $e->getMessage();
    }
    return json_encode($resp);
} 