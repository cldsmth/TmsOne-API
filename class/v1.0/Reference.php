<?php
class Reference{

	private $table = "reference";

    public function get_list(){
        $result = 0;
       
        $text = "SELECT id_reff AS id, value, caption FROM $this->table GROUP BY caption, id_reff";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $result = array();
            while($row = mysql_fetch_assoc($query)){
                $result[str_replace(" ", "_", $row['caption'])][] = $row;
            }
        }
        //$result = $text;
        return $result;
    }

}
?>