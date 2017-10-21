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

    public function update_data($id, $name, $card_name, $city, $height, $weight, $size, $work, $tempat_lahir, $birthday, $gender, $status_menikah, $warga_negara, $address, $mail_address, $religion, $phone1, $phone2, $phone3, $npwp, $instagram, $whatsapp, $bank, $cabang, $no_rek, $img, $scan, $path){
        $result = 0;
        $condImg = "";
        $condScan = "";

        if($img != ""){
            $this->remove_image($id, "photo", $path);
            $condImg = ", photo = '$img'";
        }

        if($scan != ""){
            $this->remove_image($id, "ktp_scan", $path);
            $condScan = ", ktp_scan = '$scan'";
        }

        $text = "UPDATE $this->table SET nama_lengkap = '$name', nama_kartu = '$card_name', id_kabupaten = '$city', tinggi_badan = '$height', berat_badan = '$weight', ukuran_seragam = '$size', waktu_kerja = '$work', tempat_lahir = '$tempat_lahir', tanggal_lahir = '$birthday', kelamin = '$gender',
            status_menikah = '$status_menikah', warga_negara = '$warga_negara', alamat_lengkap = '$address', alamat_surat = '$mail_address', agama = '$religion', telp1 = '$phone1', telp2 = '$phone2', telp3 = '$phone3', no_npwp = '$npwp', instagram = '$instagram', no_wa = '$whatsapp',
            nama_bank = '$bank', cabang_bank = '$cabang', no_rek = '$no_rek' $condImg $condScan WHERE id_listor = '$id'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        //$result = $text;
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

    public function remove_image($id, $field, $path){
        $result = 0;

        $text = "SELECT $field FROM $this->table WHERE id_listor = '$id'";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $row = mysql_fetch_assoc($query);
            $value = $row[$field];
            if($value != ""){
                switch ($field) {
                    case "photo":
                        $dir = "tmc_photo/";
                        break;
                    case "ktp_scan":
                        $dir = "ktp_image/";
                        break;
                    default:
                        $dir = "";
                        break;
                }
                $deleteImg = $path.$dir.$value;
                if (file_exists($deleteImg)) {
                    unlink($deleteImg);
                    $result = 1;
                }
            }
        }
        return $result;
    }

}
?>