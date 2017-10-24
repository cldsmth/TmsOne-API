<?php
class Property{

	private $table = "produk";
    private $tableRequest = "produk_request";

    public function check_exist($token, $type){
        $result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";

        $text = "SELECT id_produk$varField FROM $this->table$varTable WHERE id_produk$varField = '$token'";
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
            $cond = "AND id NOT IN(".$q_string.")";
        }else{
            $cond = "";
        }

        $textProperty = "SELECT property.id_produk AS id, property.id_owner, property.id_listor, property.judul_produk, '' AS hak, 
            province.id_provinsi, province.nama_provinsi, city.id_kabupaten, city.nama_kabupaten, kecamatan.id_kecamatan, 
            kecamatan.nama_kecamatan, kelurahan.id_kelurahan, kelurahan.nama_kelurahan, property.alamat_detail, property.kode_pos, 
            '' AS jual_beli, jenis.id_jenis, jenis.nama_jenis, property.status_properti, property.jenis_sertifikat, property.promo, 
            property.hadap_rumah, property.lebar_muka, property.panjang_dalam, property.luas_tanah, property.luas_bangunan, property.jumlah_kamar, 
            property.jumlah_kamarplus, property.jumlah_kamarmandi, '' AS jumlah_kamarmandiplus, property.jumlah_lantai, 
            '' AS daya_listrik, '' AS sumber_air, property.fasilitas, property.detail_produk, property.hashtag, property.harga, 
            property.komisi, '' AS type, property.status, property.add_date FROM $this->table property 
            LEFT JOIN jenis_produk jenis ON jenis.id_jenis = property.id_jenis
            LEFT JOIN kabupaten city ON city.id_kabupaten = property.id_kabupaten
            LEFT JOIN kecamatan kecamatan ON kecamatan.id_kecamatan = property.id_kecamatan
            LEFT JOIN kelurahan kelurahan ON kelurahan.id_kelurahan = property.id_kelurahan
            LEFT JOIN provinsi province ON province.id_provinsi = city.id_provinsi
            WHERE property.id_listor = '$user_id'";
        $textRequest = "SELECT request.id_produkrequest AS id, request.id_owner, request.id_listor, request.judul_produk, '' AS hak, 
            province.id_provinsi, province.nama_provinsi, city.id_kabupaten, city.nama_kabupaten, kecamatan.id_kecamatan, 
            kecamatan.nama_kecamatan, kelurahan.id_kelurahan, kelurahan.nama_kelurahan, request.alamat_detail, request.kode_pos, 
            '' AS jual_beli, jenis.id_jenis, jenis.nama_jenis, request.status_properti, request.jenis_sertifikat, request.promo, 
            request.hadap_rumah, request.lebar_muka, request.panjang_dalam, request.luas_tanah, request.luas_bangunan, request.jumlah_kamar, 
            request.jumlah_kamarplus, request.jumlah_kamarmandi, '' AS jumlah_kamarmandiplus, request.jumlah_lantai, 
            '' AS daya_listrik, '' AS sumber_air, request.fasilitas, request.detail_produk, request.hashtag, request.harga, 
            request.komisi, 'request' AS type, 0 AS status, request.add_date FROM $this->tableRequest request 
            LEFT JOIN jenis_produk jenis ON jenis.id_jenis = request.id_jenis
            LEFT JOIN kabupaten city ON city.id_kabupaten = request.id_kabupaten
            LEFT JOIN kecamatan kecamatan ON kecamatan.id_kecamatan = request.id_kecamatan
            LEFT JOIN kelurahan kelurahan ON kelurahan.id_kelurahan = request.id_kelurahan
            LEFT JOIN provinsi province ON province.id_provinsi = city.id_provinsi
            WHERE request.id_listor = '$user_id'";
        
        $text = "SELECT * FROM (($textProperty) UNION ALL ($textRequest)) 
            items HAVING status IN(0,1) $cond ORDER BY add_date ASC";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $result = array();
            $loop = 0;
            while($row = mysql_fetch_assoc($query)){
                $result[$loop] = $row;
                $varField = $row['type'] == "" ? "" : "request";
                $varTable = $row['type'] == "" ? "" : "_request";

                $text_detail = "SELECT id_photo, id_produk$varField AS id_property, file_photo
                	FROM photo_produk$varTable WHERE id_produk$varField = '{$row['id']}'";
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

    public function insert_data($user_id, $owner, $title, $hak, $city, $kecamatan, $kelurahan, $address, $zip, $jual_beli, $jenis, $status_property, 
            $sertifikat, $promo, $menghadap, $lebar_depan, $panjang_tanah, $luas_tanah, $luas_bangunan, $bed, $bed_plus, $bath, $bath_plus, $floor, 
            $daya_listrik, $sumber_air, $fasiltas, $description, $hashtag, $price, $komisi, $type, $status, $create_date){
		$result = 0;
        $fieldStatus = $type == "" ? "status," : "";
        $valStatus = $type == "" ? "'$status'," : "";
        $varTable = $type == "" ? "" : "_request";

		$text = "INSERT INTO $this->table$varTable (id_listor, id_owner, judul_produk, id_kabupaten, id_kecamatan, id_kelurahan, alamat_detail, kode_pos, id_jenis, 
            status_properti, jenis_sertifikat, promo, hadap_rumah, lebar_muka, panjang_dalam, luas_tanah, luas_bangunan, jumlah_kamar, jumlah_kamarplus, 
            jumlah_kamarmandi, jumlah_lantai, fasilitas, detail_produk, hashtag, harga, komisi, $fieldStatus add_date) VALUES ('$user_id', '$owner', '$title', 
            '$city', '$kecamatan', '$kelurahan', '$address', '$zip', '$jenis', '$status_property', '$sertifikat', '$promo', '$menghadap', '$lebar_depan', 
            '$panjang_tanah', '$luas_tanah', '$luas_bangunan', '$bed', '$bed_plus', '$bath', '$floor', '$fasiltas', '$description', '$hashtag', '$price', 
            '$komisi', $valStatus '$create_date')";
		$query = mysql_query($text);
		if($query){
			$result = mysql_insert_id();
		}
        //$result = $text;
		return $result;
	}

    public function update_data($token, $owner, $title, $hak, $city, $kecamatan, $kelurahan, $address, $zip, $jual_beli, 
            $jenis, $status_property, $sertifikat, $promo, $menghadap, $lebar_depan, $panjang_tanah, $luas_tanah, $luas_bangunan, $bed, 
            $bed_plus, $bath, $bath_plus, $floor, $daya_listrik, $sumber_air, $fasiltas, $description, $hashtag, $price, $komisi, $type){
        $result = 0;

        $text = "UPDATE $this->table SET property_owner = '$owner', property_title = '$title', property_hak = '$hak', property_province = '$province', 
            property_city = '$city', property_kecamatan = '$kecamatan', property_kelurahan = '$kelurahan', property_address = '$address', property_zip = '$zip', 
            property_jual_beli = '$jual_beli', property_type = '$jenis', property_status_property = '$status_property', property_sertifikat = '$sertifikat', 
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