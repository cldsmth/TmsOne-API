<?php
class User{

	private $table = "t_user";

    public function check_password($id, $password){
        $result = 0;
    
        $text = "SELECT user_password FROM $this->table WHERE user_id = '$id' AND user_password = '$password' LIMIT 0,1";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $result = 1;
        }
        return $result;
    }

    public function check_code($auth_token, $id){//check the token and id before changing the content
        $result = 0;

        $text = "SELECT user_id FROM $this->table WHERE user_auth_token = '$auth_token' AND user_id = '$id' LIMIT 0,1";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $result = 1;//can be used
        }
        return $result;  
    }

    public function login($email, $password){
        $result = 0;//FAILED
        
        $text = "SELECT user_id, user_no_tmc, user_name, user_card_name, user_email, user_province, user_city,
            user_height, user_weight, user_size, user_work, user_tempat_lahir, user_birthday, user_gender,
            user_status_menikah, user_warga_negara, user_ktp, user_address, user_mail_address, user_religion,
            user_phone1, user_phone2, user_phone3, user_npwp, user_instagram, user_whatsapp, user_bank, 
            user_cabang, user_no_rek, user_img, user_scan, user_auth_token FROM $this->table 
            WHERE user_email = '$email' AND user_password = '$password' LIMIT 0,1";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){//HAS TO BE EXACT 1 RESULT
            $row = mysql_fetch_assoc($query);
            $result = $row;
        }
        //$result = $text;
        return $result;
    }

    public function get_forget_password($email){
        $result = 0;
    
        $text = "SELECT user_id, user_name, user_email, user_password 
            FROM $this->table WHERE user_email = '$email' LIMIT 0,1";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $result = array();
            while($row = mysql_fetch_assoc($query)){
                $result[] = $row;
            }
        }
        return $result;
    }

    public function update_data($id, $name, $card_name, $province, $city, $height, $weight, $size, $work, $tempat_lahir, $birthday, $gender, $status_menikah, $warga_negara, $address, $mail_address, $religion, $phone1, $phone2, $phone3, $npwp, $instagram, $whatsapp, $bank, $cabang, $no_rek, $img, $img_thmb, $scan, $scan_thmb, $path){
        $result = 0;
        $condImg = "";
        if($img != ""){
            $this->remove_image_field($id, "user_img", "user_img_thmb", $path);
            $condImg = ", user_img = '$img', user_img_thmb = '$img_thmb'";
        }
        $condScan = "";
        if($scan != ""){
            $this->remove_image_field($id, "user_scan", "user_scan_thmb", $path);
            $condScan = ", user_scan = '$scan', user_scan_thmb = '$scan_thmb'";
        }

        $text = "UPDATE $this->table SET user_name = '$name', user_card_name = '$card_name', user_province = '$province', user_city = '$city', user_height = '$height', user_weight = '$weight', user_size = '$size', user_work = '$work', user_tempat_lahir = '$tempat_lahir', user_birthday = '$birthday', user_gender = '$gender',
            user_status_menikah = '$status_menikah', user_warga_negara = '$warga_negara', user_address = '$address', user_mail_address = '$mail_address', user_religion = '$religion', user_phone1 = '$phone1', user_phone2 = '$phone2', user_phone3 = '$phone3', user_npwp = '$npwp', user_instagram = '$instagram', user_whatsapp = '$whatsapp',
            user_bank = '$bank', user_cabang = '$cabang', user_no_rek = '$no_rek' $condImg $condScan WHERE user_id = '$id'";
        $query = mysql_query($text);    
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function update_password($id, $old_password, $new_password){
        $result = 0;

        $text = "UPDATE $this->table SET user_password = '$new_password' WHERE user_id = '$id' AND user_password = '$old_password'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function remove_image_field($id, $field, $field_thmb, $path){
        $result = 0;
        $flag_img = 0;
        $flag_img_thmb = 0;

        $text = "SELECT $field, $field_thmb FROM $this->table WHERE user_id = '$id'";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $row = mysql_fetch_assoc($query);
            if($row[$field] != "" && $row[$field_thmb] != ""){
                $deleteImg = $path.$row[$field];
                if (file_exists($deleteImg)) {
                    unlink($deleteImg);
                    $flag_img = 1;
                }

                $deleteImgThmb = $path.$row[$field_thmb];
                if (file_exists($deleteImgThmb)) {
                    unlink($deleteImgThmb);
                    $flag_img_thmb = 1;
                }
                
                if($flag_img == 1 && $flag_img_thmb ==1){
                    $result = 1;
                }
            }
        }
        return $result;
    }

}
?>