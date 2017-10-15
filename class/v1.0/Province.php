<?php
class Province{

	private $table = "t_province";

    public function get_list(){
        $result = 0;
       
        $text = "SELECT id_prov, nama FROM $this->table";
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