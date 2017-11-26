<?php
class PropertyImage{

	private $table = "photo_produk";

    public function get_image_sync_by_property($property, $type, $datas){
        $result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";

        $data = json_decode($datas);
        if(is_array($data)){
            $q_string = "";
            $count = 1;
            foreach($data as $i){
                $seperate = $count == count($data) ? "" : ",";
                $q_string .= "'".$i->token."'".$seperate;
                $count++;
            }
            $cond = "AND id_photo NOT IN(".$q_string.")";
        }else{
            $cond = "";
        }

        $text = "SELECT id_photo, id_produk$varField AS id_property, file_photo, is_primary, add_date 
            FROM $this->table$varTable WHERE id_produk$varField = '$property' $cond";
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

	public function insert_data($property, $type, $img, $primary, $create_date){
		$result = 0;
        $varField = $type == "" ? "" : "request";
        $varTable = $type == "" ? "" : "_request";

		$text = "INSERT INTO $this->table$varTable (id_produk$varField, file_photo, is_primary, add_date) 
            VALUES ('$property', '$img', '$primary', '$create_date')";
		$query = mysql_query($text);
		if($query){
			$result = mysql_insert_id();
		}
        //$result = $text;
		return $result;
	}

	public function delete_data($token, $type, $path){
        $result = 0;
        $varTable = $type == "" ? "" : "_request";
        $this->remove_image($token, $type, $path); //remove image before

        $text = "DELETE FROM $this->table$varTable WHERE id_photo = '$token'";
        $query = mysql_query($text);
        if(mysql_affected_rows() == 1){
            $result = 1;
        }
        return $result;
    }

    public function remove_image($token, $type, $path){
        $result = 0;
        $varTable = $type == "" ? "" : "_request";

        $text = "SELECT id_photo, file_photo FROM $this->table$varTable WHERE id_photo = '$token'";
        $query = mysql_query($text);
        if(mysql_num_rows($query) == 1){
            $row = mysql_fetch_assoc($query);
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