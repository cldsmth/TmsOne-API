<?php
class Kelurahan{

	private $table = "kelurahan";

    public function get_list_by_kecamatan($kecamatan){
        $result = 0;
       
        $text = "SELECT id_kelurahan, nama_kelurahan FROM $this->table 
            WHERE id_kecamatan = '$kecamatan' ORDER BY nama_kelurahan ASC";
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