<?php
class Jenis{

	private $table = "jenis_produk";

    public function get_list(){
        $result = 0;
       
        $text = "SELECT id_jenis, nama_jenis FROM $this->table";
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