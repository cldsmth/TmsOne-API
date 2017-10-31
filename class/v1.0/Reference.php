<?php
class Reference{

	private $table = "reference";

    public function get_list_by_caption($caption){
        $result = 0;
       
        $text = "SELECT id_reff, value FROM $this->table WHERE caption = '$caption'";
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