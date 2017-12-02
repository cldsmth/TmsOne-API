<?php
class Kecamatan{

	private $table = "kecamatan";

    public function get_kecamatan_sync($last_updated){
        $result = 0;
        $cond = $last_updated != "" ? "WHERE (UNIX_TIMESTAMP(timestamp) * 1000) > 
            (UNIX_TIMESTAMP('$last_updated') * 1000)" : "";
       
        $text = "SELECT id_kecamatan, id_kabupaten, nama_kecamatan, timestamp 
            FROM $this->table $cond ORDER BY nama_kecamatan ASC";
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

    public function get_list_by_city($city){
        $result = 0;
       
        $text = "SELECT id_kecamatan, nama_kecamatan FROM $this->table 
            WHERE id_kabupaten = '$city' ORDER BY nama_kecamatan ASC";
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