<?php
class Property{

	private $table = "t_property";

    public function check_exist($token){
        $result = 0;

        $text = "SELECT property_token FROM $this->table WHERE property_token = '$token'";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $row = mysql_fetch_array($query, MYSQL_ASSOC);
            $result = 1;
        }
        //$result = $text;
        return $result;
    }

	public function get_property_sync($user_id, $datas){
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
            $cond = "AND property_token NOT IN(".$q_string.")";
        }else{
            $cond = "";
        }

        $text = "SELECT property_id, property_token, property_owner, property_title, property_hak, property_province,
            property_city, property_kecamatan, property_kelurahan, property_address, property_zip, property_jual_beli, 
            property_type, property_status_property, property_sertifikat, property_promo, property_menghadap, 
            property_lebar_depan, property_panjang_tanah, property_luas_tanah, property_luas_bangunan, property_bed, 
            property_bed_plus, property_bath, property_bath_plus, property_floor, property_daya_listrik, property_sumber_air,
            property_fasilitas, property_description, property_hashtag, property_price, property_komisi, property_status, 
            property_create_date FROM $this->table WHERE property_status = 'success' $cond ORDER BY property_create_date ASC";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $result = array();
            $loop = 0;
            while($row = mysql_fetch_assoc($query)){
                $result[$loop] = $row;

                $text_detail = "SELECT pi_id, pi_token, pi_property, pi_img, pi_img_thmb, pi_status 
                	FROM t_property_image WHERE pi_property = '{$row['property_token']}'";
                $query_detail = mysql_query($text_detail);
                if(mysql_num_rows($query_detail) >= 1){
                    while($row_detail = mysql_fetch_array($query_detail, MYSQL_ASSOC)){
                        $result[$loop]['image'][] = $row_detail;
                    }
                }
                $loop++;
            }
        }
        //$result = $text;
        return $result;
    }

    public function insert_data($token, $owner, $title, $hak, $province, $city, $kecamatan, $kelurahan, $address, $zip, $jual_beli, 
            $type, $status_property, $sertifikat, $promo, $menghadap, $lebar_depan, $panjang_tanah, $luas_tanah, $luas_bangunan, $bed, 
            $bed_plus, $bath, $bath_plus, $floor, $daya_listrik, $sumber_air, $fasiltas, $description, $hashtag, $price, $komisi, 
            $status, $create_date){
		$result = 0;

		$text = "INSERT INTO $this->table (property_token, property_owner, property_title, property_hak, property_province, property_city, 
            property_kecamatan, property_kelurahan, property_address, property_zip, property_jual_beli, property_type, property_status_property, 
            property_sertifikat, property_promo, property_menghadap, property_lebar_depan, property_panjang_tanah, property_luas_tanah, 
            property_luas_bangunan, property_bed, property_bed_plus, property_bath, property_bath_plus, property_floor, property_daya_listrik,
            property_sumber_air, property_fasilitas, property_description, property_hashtag, property_price, property_komisi, property_status, property_create_date) 
			VALUES('$token', '$owner', '$title', '$hak', '$province', '$city', '$kecamatan', '$kelurahan', '$address', '$zip', '$jual_beli', '$type', 
            '$status_property', '$sertifikat', '$promo', '$menghadap', '$lebar_depan', '$panjang_tanah', '$luas_tanah', '$luas_bangunan', '$bed', 
            '$bed_plus', '$bath', '$bath_plus', '$floor', '$daya_listrik', '$sumber_air', '$fasiltas', '$description', '$hashtag', '$price', '$komisi', 
            '$status', '$create_date')";
		$query = mysql_query($text);
		if($query){
			$result = 1;
		}
        //$result = $text;
		return $result;
	}

    public function update_data($token, $owner, $title, $hak, $province, $city, $kecamatan, $kelurahan, $address, $zip, $jual_beli, 
            $type, $status_property, $sertifikat, $promo, $menghadap, $lebar_depan, $panjang_tanah, $luas_tanah, $luas_bangunan, $bed, 
            $bed_plus, $bath, $bath_plus, $floor, $daya_listrik, $sumber_air, $fasiltas, $description, $hashtag, $price, $komisi){
        $result = 0;

        $text = "UPDATE $this->table SET property_owner = '$owner', property_title = '$title', property_hak = '$hak', property_province = '$province', 
            property_city = '$city', property_kecamatan = '$kecamatan', property_kelurahan = '$kelurahan', property_address = '$address', property_zip = '$zip', 
            property_jual_beli = '$jual_beli', property_type = '$type', property_status_property = '$status_property', property_sertifikat = '$sertifikat', 
            property_promo = '$promo', property_menghadap = '$menghadap', property_lebar_depan = '$lebar_depan', property_panjang_tanah = '$panjang_tanah', 
            property_luas_tanah = '$luas_tanah', property_luas_bangunan = '$luas_bangunan', property_bed = '$bed', property_bed_plus = '$bed_plus', property_bath = '$bath', 
            property_bath_plus = '$bath_plus', property_floor = '$floor', property_daya_listrik = '$daya_listrik', property_sumber_air = '$sumber_air',
            property_fasilitas = '$fasiltas', property_description = '$description', property_hashtag = '$hashtag', property_price = '$price', property_komisi = '$komisi' 
            WHERE property_token = '$token'";
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
        $this->delete_data_image($token); //delete data image

        $text = "DELETE FROM $this->table WHERE property_token = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function delete_data_by_owner($owner, $path){
        $result = 0;
        $this->remove_image_by_owner($owner, $path); //remove image before
        $this->delete_data_image_by_owner($owner); //delete data image

        $text = "DELETE FROM $this->table WHERE property_owner = '$owner'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function delete_data_image($token){
        $result = 0;
        
        $text = "DELETE FROM t_property_image WHERE pi_property = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function delete_data_image_by_owner($owner){
        $result = 0;
        
        $text = "DELETE FROM t_property_image WHERE pi_property IN
            (SELECT property_token FROM $this->table WHERE property_owner = '$owner')";
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

        $text = "SELECT pi_img, pi_img_thmb FROM t_property_image WHERE pi_property = '$token'";
        $query = mysql_query($text);
        while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
            $deleteImg = $path.$row['pi_img'];
            if(file_exists($deleteImg)){
                unlink($deleteImg);
                $flag_img = 1;
            }

            $deleteImgThmb = $path.$row['pi_img_thmb'];
            if(file_exists($deleteImgThmb)){
                unlink($deleteImgThmb);
                $flag_img_thmb = 1;
            }

            if($flag_img == 1 && $flag_img_thmb ==1){
                $result = 1;
            }
        }
        return $result;
    }

    public function remove_image_by_owner($owner, $path){
        $result = 0;
        $flag_img = 0;
        $flag_img_thmb = 0;

        $text = "SELECT pi_img, pi_img_thmb FROM t_property_image LEFT JOIN t_property 
            ON property_token = pi_property WHERE property_owner = '$owner'";
        $query = mysql_query($text);
        while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
            $deleteImg = $path.$row['pi_img'];
            if(file_exists($deleteImg)){
                unlink($deleteImg);
                $flag_img = 1;
            }

            $deleteImgThmb = $path.$row['pi_img_thmb'];
            if(file_exists($deleteImgThmb)){
                unlink($deleteImgThmb);
                $flag_img_thmb = 1;
            }

            if($flag_img == 1 && $flag_img_thmb ==1){
                $result = 1;
            }
        }
        return $result;
    }

}
?>