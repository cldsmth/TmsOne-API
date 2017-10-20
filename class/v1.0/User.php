<?php
class User{

	private $table = "listor";
    private $joinMember = "LEFT JOIN member member ON member.id_member = listor.id_member";
    private $joinCity = "LEFT JOIN kabupaten city ON city.id_kabupaten = listor.id_kabupaten";
    private $joinProvince = "LEFT JOIN provinsi province ON province.id_provinsi = city.id_provinsi";

    public function check_password($id, $password){
        $result = 0;
    
        $text = "SELECT member.password FROM $this->table listor $this->joinMember 
            WHERE listor.id_listor = '$id' AND member.password = '$password' LIMIT 0,1";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $result = 1;
        }
        return $result;
    }

    public function check_code($auth_token, $id){//check the token and id before changing the content
        $result = 0;

        $text = "SELECT id_listor FROM $this->table WHERE listor_number = '$auth_token' AND id_listor = '$id' LIMIT 0,1";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $result = 1;//can be used
        }
        return $result;  
    }

    public function login($email, $password){
        $result = 0;//FAILED
        
        $text = "SELECT listor.id_listor, listor.listor_number, listor.nama_lengkap, listor.nama_kartu, listor.email, 
            province.id_provinsi, province.nama_provinsi, city.id_kabupaten, city.nama_kabupaten, listor.tinggi_badan, 
            listor.berat_badan, listor.ukuran_seragam, listor.waktu_kerja, listor.tempat_lahir, listor.tanggal_lahir, 
            listor.kelamin, listor.status_menikah, listor.warga_negara, listor.no_ktp, listor.alamat_lengkap, 
            listor.alamat_surat, listor.agama, listor.telp1, listor.telp2, listor.telp3, listor.no_npwp, listor.instagram, 
            listor.no_wa, listor.nama_bank, listor.cabang_bank, listor.no_rek, listor.photo, listor.ktp_scan FROM 
            $this->table listor $this->joinMember $this->joinCity $this->joinProvince WHERE member.email = '$email' 
            AND member.password = '$password' AND member.reg_status = 1 LIMIT 0,1";
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

        $text = "UPDATE member member SET member.password = '$new_password' WHERE member.id_member =
            (SELECT id_member FROM $this->table WHERE id_listor = '$id') AND member.password = '$old_password'";
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