<?php
class Reference{

	private $table = "reference";

    public function get_reference_sync($last_updated){
        $result = 0;
        $cond = $last_updated != "" ? "WHERE (UNIX_TIMESTAMP(timestamp) * 1000) > 
            (UNIX_TIMESTAMP('$last_updated') * 1000)" : "";
       
        $text = "SELECT id_reff, value, caption, timestamp FROM $this->table $cond";
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

    public function get_last_updated(){
        $result = 0;

        $text = "SELECT MAX(timestamp) AS last_updated FROM $this->table";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $row = mysql_fetch_assoc($query);
            $result = $row['last_updated'];
        }
        return $result;
    }

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