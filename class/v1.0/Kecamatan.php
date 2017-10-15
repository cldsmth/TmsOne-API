<?php
class Kecamatan{

	private $table = "t_kecamatan";

    public function get_list_by_city($city){
        $result = 0;
       
        $text = "SELECT id_kec, nama FROM $this->table WHERE id_kab = '$city'";
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