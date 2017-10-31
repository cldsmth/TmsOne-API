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

        $textProperty = "SELECT property.id_produk AS id, property.id_owner, property.id_listor, property.judul_produk, property.hak_jual, 
            province.id_provinsi, province.nama_provinsi, city.id_kabupaten, city.nama_kabupaten, kecamatan.id_kecamatan, 
            kecamatan.nama_kecamatan, kelurahan.id_kelurahan, kelurahan.nama_kelurahan, property.alamat_detail, property.kode_pos, 
            property.status_jual, jenis.id_jenis, jenis.nama_jenis, property.status_properti, property.jenis_sertifikat, property.promo, 
            property.hadap_rumah, property.lebar_muka, property.panjang_dalam, property.luas_tanah, property.luas_bangunan, property.jumlah_kamar, 
            property.jumlah_kamarplus, property.jumlah_kamarmandi, property.jumlah_kamarmandiplus, property.jumlah_lantai, 
            property.daya_listrik, property.sumber_air, property.fasilitas, property.detail_produk, property.hashtag, property.harga, 
            property.komisi, '' AS type, property.status, property.add_date FROM $this->table property 
            LEFT JOIN jenis_produk jenis ON jenis.id_jenis = property.id_jenis
            LEFT JOIN kabupaten city ON city.id_kabupaten = property.id_kabupaten
            LEFT JOIN kecamatan kecamatan ON kecamatan.id_kecamatan = property.id_kecamatan
            LEFT JOIN kelurahan kelurahan ON kelurahan.id_kelurahan = property.id_kelurahan
            LEFT JOIN provinsi province ON province.id_provinsi = city.id_provinsi
            WHERE property.id_listor = '$user_id'";
        $textRequest = "SELECT request.id_produkrequest AS id, request.id_owner, request.id_listor, request.judul_produk, request.hak_jual, 
            province.id_provinsi, province.nama_provinsi, city.id_kabupaten, city.nama_kabupaten, kecamatan.id_kecamatan, 
            kecamatan.nama_kecamatan, kelurahan.id_kelurahan, kelurahan.nama_kelurahan, request.alamat_detail, request.kode_pos, 
            request.status_jual, jenis.id_jenis, jenis.nama_jenis, request.status_properti, request.jenis_sertifikat, request.promo, 
            request.hadap_rumah, request.lebar_muka, request.panjang_dalam, request.luas_tanah, request.luas_bangunan, request.jumlah_kamar, 
            request.jumlah_kamarplus, request.jumlah_kamarmandi, request.jumlah_kamarmandiplus, request.jumlah_lantai, 
            request.daya_listrik, request.sumber_air, request.fasilitas, request.detail_produk, request.hashtag, request.harga, 
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

		$text = "INSERT INTO $this->table$varTable (id_listor, id_owner, judul_produk, hak_jual, id_kabupaten, id_kecamatan, id_kelurahan, alamat_detail, 
            kode_pos, status_jual, id_jenis, status_properti, jenis_sertifikat, promo, hadap_rumah, lebar_muka, panjang_dalam, luas_tanah, luas_bangunan, jumlah_kamar, 
            jumlah_kamarplus, jumlah_kamarmandi, jumlah_kamarmandiplus, jumlah_lantai, daya_listrik, sumber_air, fasilitas, detail_produk, hashtag, harga, komisi, $fieldStatus add_date) 
            VALUES ('$user_id', '$owner', '$title', '$hak', '$city', '$kecamatan', '$kelurahan', '$address', '$zip', '$jual_beli', '$jenis', '$status_property', '$sertifikat', '$promo', 
            '$menghadap', '$lebar_depan', '$panjang_tanah', '$luas_tanah', '$luas_bangunan', '$bed', '$bed_plus', '$bath', '$bath_plus', '$floor', '$daya_listrik', '$sumber_air', '$fasiltas', 
            '$description', '$hashtag', '$price', '$komisi', $valStatus '$create_date')";
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
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";

        $text = "UPDATE $this->table$varTable SET id_owner = '$owner', judul_produk = '$title', hak_jual = '$hak_jual', id_kabupaten = '$city', id_kecamatan = '$kecamatan', 
            id_kelurahan = '$kelurahan', alamat_detail = '$address', kode_pos = '$zip', status_jual = '$jual_beli', id_jenis = '$jenis', status_properti = '$status_property', 
            jenis_sertifikat = '$sertifikat', promo = '$promo', hadap_rumah = '$menghadap', lebar_muka = '$lebar_depan', panjang_dalam = '$panjang_tanah', 
            luas_tanah = '$luas_tanah', luas_bangunan = '$luas_bangunan', jumlah_kamar = '$bed', jumlah_kamarplus = '$bed_plus', jumlah_kamarmandi = '$bath', 
            jumlah_kamarmandiplus = '$bath_plus', jumlah_lantai = '$floor', daya_listrik = '$daya_listrik', sumber_air = '$sumber_air', fasilitas = '$fasiltas', 
            detail_produk = '$description', hashtag = '$hashtag', harga = '$price', komisi = '$komisi' WHERE id_produk$varField = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        //$result = $text;
        return $result;
    }

    public function delete_data($token, $type, $path){
        $result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";
        $this->remove_image($token, $type, $path); //remove image before
        $this->delete_data_image($token, $type); //delete data image

        $text = "DELETE FROM $this->table$varTable WHERE id_produk$varField = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function delete_data_by_owner($owner, $type, $path){
        $result = 0;
        $varTable = $type == "" ? "" : "_request";
        $this->remove_image_by_owner($owner, $type, $path); //remove image before
        $this->delete_data_image_by_owner($owner, $type); //delete data image
       
        $text = "DELETE FROM $this->table$varTable WHERE id_owner = '$owner'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function delete_data_image($token, $type){
        $result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";
        
        $text = "DELETE FROM photo_produk$varTable WHERE id_produk$varField = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function delete_data_image_by_owner($owner, $type){
        $result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";
        
        $text = "DELETE FROM photo_produk$varTable WHERE id_produk$varField IN
            (SELECT property.id_produk$varField FROM $this->table$varTable property 
                WHERE property.id_owner = '$owner')";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function remove_image($token, $type, $path){
        $result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";

        $text = "SELECT id_photo, file_photo FROM photo_produk$varTable WHERE id_produk$varField = '$token'";
        $query = mysql_query($text);
        while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
            $value = $row['file_photo'];
            if($value != ""){
                $deleteImg = $path."produk_photo/".$value;
                if (file_exists($deleteImg)) {
                    unlink($deleteImg);
                    $result = 1;
                }
            }
        }
        return $result;
    }

    public function remove_image_by_owner($owner, $type, $path){
        $result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";

        $text = "SELECT photo.id_photo, photo.file_photo FROM photo_produk$varTable photo 
            LEFT JOIN $this->table$varTable property ON property.id_produk$varField = photo.id_produk$varField 
            WHERE property.id_owner = '$owner'";
        $query = mysql_query($text);
        while($row = mysql_fetch_array($query, MYSQL_ASSOC)){
            $value = $row['file_photo'];
            if($value != ""){
                $deleteImg = $path."produk_photo/".$value;
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