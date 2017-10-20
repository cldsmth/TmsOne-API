<?php
class Kecamatan{

	private $table = "kecamatan";

    public function get_list_by_city($city){
        $result = 0;
       
        $text = "SELECT id_kecamatan, nama_kecamatan FROM $this->table 
            WHERE id_kabupaten = '$city' ORDER BY nama_kecamatan ASC";
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

}
?>