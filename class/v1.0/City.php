<?php
class City{

	private $table = "kabupaten";

    public function get_list_by_province($province){
        $result = 0;
       
        $text = "SELECT id_kabupaten, nama_kabupaten FROM $this->table 
            WHERE status = 1 AND id_provinsi = '$province' ORDER BY nama_kabupaten ASC";
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