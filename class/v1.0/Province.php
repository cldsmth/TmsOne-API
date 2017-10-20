<?php
class Province{

	private $table = "provinsi";

    public function get_list(){
        $result = 0;
       
        $text = "SELECT id_provinsi, nama_provinsi FROM $this->table 
            WHERE status = 1 ORDER BY nama_provinsi ASC";
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