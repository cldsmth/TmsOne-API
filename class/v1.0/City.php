<?php
class City{

	private $table = "mst_subarea";

    public function get_list_by_province($province){
        $result = 0;
       
        $text = "SELECT id_subarea, nama_subarea FROM $this->table 
            WHERE id_area = '$province' AND status = 1";
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