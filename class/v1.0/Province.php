<?php
class Province{

	private $table = "mst_area";

    public function get_list(){
        $result = 0;
       
        $text = "SELECT id_area, nama_area FROM $this->table WHERE status = 1 ORDER BY nama_area ASC";
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