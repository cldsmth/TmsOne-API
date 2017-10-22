<?php
class Owner{

	private $table = "owner";
    private $itemPerPage = 6;
    private $joinProperty = "LEFT JOIN produk property ON property.id_owner = owner.id_owner";
    private $joinCity = "LEFT JOIN kabupaten city ON city.id_kabupaten = owner.id_kabupaten";
    private $joinKecamatan = "LEFT JOIN kecamatan kecamatan ON kecamatan.id_kecamatan = owner.id_kecamatan";
    private $joinKelurahan = "LEFT JOIN kelurahan kelurahan ON kelurahan.id_kelurahan = owner.id_kelurahan";
    private $joinProvince = "LEFT JOIN provinsi province ON province.id_provinsi = city.id_provinsi";

    public function check_exist($token){
        $result = 0;

        $text = "SELECT owner_token FROM $this->table WHERE owner_token = '$token'";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $row = mysql_fetch_array($query, MYSQL_ASSOC);
            $result = 1;
        }
        //$result = $text;
        return $result;
    }

    public function get_owner_sync($user_id, $datas){
        $result = 0;
        $data = json_decode($datas);
        if(is_array($data)){
            $q_string = "";
            $count = 1;
            foreach($data as $i){
                $seperate = $count == count($data) ? "" : ",";
                $q_string .= "'".$i->token."'".$seperate;
                $count++;
            }
            $cond = "AND owner.id_owner NOT IN(".$q_string.")";
        }else{
            $cond = "";
        }

        $text = "SELECT COUNT(property.id_produk) AS counter, owner.id_owner, owner.id_listor, owner.alamat_lengkap, 
            owner.email, owner.tempat_lahir, owner.tanggal_lahir, owner.kelamin, province.id_provinsi, province.nama_provinsi, 
            city.id_kabupaten, city.nama_kabupaten, kecamatan.id_kecamatan, kecamatan.nama_kecamatan, kelurahan.id_kelurahan, 
            kelurahan.nama_kelurahan, owner.alamat_lengkap, owner.telp1, owner.telp2, owner.telp3, owner.no_ktp, owner.photo, 
            owner.status, owner.add_date FROM $this->table $this->joinProperty $this->joinCity $this->joinKecamatan $this->joinKelurahan
            $this->joinProvince WHERE owner.status = 1 AND owner.id_listor = '$user_id' $cond GROUP BY owner.id_owner ORDER BY owner.add_date ASC";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $result = array();
            while($row = mysql_fetch_assoc($query)){
                $result[] = $row;
            }
        }
        //$result = $text;
        return $result;
    }

    public function get_owner($page=1, $keyword){
        $result = 0;
        $cond = "";
        if($keyword != ""){
            $keywords = explode(" ", $keyword);
            if(is_array($keywords)){
                $q_string = "";
                $last_index = intval(count($keywords)) - 1;
                for($i = 0; $i < count($keywords); $i++){
                    $q_string = $q_string . " nama_lengkap LIKE '%".$keywords[$i]."%' ";
                    if($i != $last_index){
                      $q_string = $q_string . " OR ";
                    }
                }
                $cond = "AND ".$q_string;
            }
        }

        //get total data
        $text_total = "SELECT id_owner FROM $this->table WHERE status = 1 $cond";
        $query_total = mysql_query($text_total);
        $total_data = mysql_num_rows($query_total);
        $total_data = $total_data < 1 ? 0 : $total_data;

        //get total page
        $total_page = ceil($total_data / $this->itemPerPage);
        $limitBefore = $page <= 1 || $page == null ? 0 : ($page-1) * $this->itemPerPage;

        $text = "SELECT * FROM $this->table WHERE status = 1 $cond
            ORDER BY add_date DESC LIMIT $limitBefore, $this->itemPerPage";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $result = array();
            while($row = mysql_fetch_assoc($query)){
                $result[] = $row;
            }
        }
        if(is_array($result)){
            $result[0]['total_page'] = $total_page;
            $result[0]['total_data_all'] = $total_data;
            $result[0]['total_data'] = count($result);
        }
        //$result = $text;
        return $result;
    }

    public function insert_data($user_id, $token, $name, $email, $tempat_lahir, $birthday, $gender, $province, $city, $kecamatan, $kelurahan, $address, $phone1, $phone2, $phone3, $ktp, $img, $img_thmb, $status, $create_date){
        $result = 0;

        $text = "INSERT INTO $this->table (owner_user_id, owner_token, owner_name, owner_email, owner_tempat_lahir, owner_birthday, owner_gender, owner_province, owner_city, owner_kecamatan, owner_kelurahan, owner_address, owner_phone1, owner_phone2, owner_phone3, owner_ktp, owner_img, owner_img_thmb, owner_status, owner_create_date) 
            VALUES ('$user_id', '$token', '$name', '$email', '$tempat_lahir', '$birthday', '$gender', '$province', '$city', '$kecamatan', '$kelurahan', '$address', '$phone1', '$phone2', '$phone3', '$ktp', '$img', '$img_thmb', '$status', '$create_date')";
        $query = mysql_query($text);
        if($query){
            $result = 1;
        }
        //$result = $text;
        return $result;
    }

    public function update_data($token, $name, $email, $tempat_lahir, $birthday, $gender, $province, $city, $kecamatan, $kelurahan, $address, $phone1, $phone2, $phone3, $ktp, $img, $img_thmb, $path){
        $result = 0;
        $cond = "";
        if($img != "" && $img_thmb != ""){
            $this->remove_image($token, $path); //remove image before
            $cond = ", owner_img = '$img', owner_img_thmb = '$img_thmb'";
        }

        $text = "UPDATE $this->table SET owner_name = '$name', owner_email = '$email', owner_tempat_lahir = '$tempat_lahir', owner_birthday = '$birthday', owner_gender = '$gender', owner_province = '$province', 
            owner_city = '$city', owner_kecamatan = '$kecamatan', owner_kelurahan = '$kelurahan', owner_address = '$address', owner_phone1 = '$phone1', owner_phone2 = '$phone2', owner_phone3 = '$phone3', 
            owner_ktp = '$ktp' $cond WHERE owner_token = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        //$result = $text;
        return $result;
    }

    public function delete_data($token, $path){
        $result = 0;
        $this->remove_image($token, $path); //remove image before

        $text = "DELETE FROM $this->table WHERE owner_token = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function remove_image($token, $path){
        $result = 0;
        $flag_img = 0;
        $flag_img_thmb = 0;

        $text = "SELECT owner_id, owner_img, owner_img_thmb FROM $this->table WHERE owner_token = '$token'";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $row = mysql_fetch_assoc($query);
            if($row['owner_img'] != "" && $row['owner_img_thmb'] != ""){
                $deleteImg = $path.$row['owner_img'];
                if (file_exists($deleteImg)) {
                    unlink($deleteImg);
                    $flag_img = 1;
                }

                $deleteImgThmb = $path.$row['owner_img_thmb'];
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