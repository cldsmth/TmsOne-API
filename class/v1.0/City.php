<?php
class City{

	private $table = "kabupaten";

    public function get_city_sync($last_updated){
        $result = 0;
        $cond = $last_updated != "" ? "AND (UNIX_TIMESTAMP(timestamp) * 1000) > 
            (UNIX_TIMESTAMP('$last_updated') * 1000)" : "";
       
        $text = "SELECT id_kabupaten, id_provinsi, kode_kabupaten, nama_kabupaten, 
            keterangan, status, timestamp FROM $this->table WHERE status = 1 $cond";
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

        $text = "SELECT MAX(timestamp) AS last_updated FROM $this->table WHERE status = 1";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $row = mysql_fetch_assoc($query);
            $result = $row['last_updated'];
        }
        return $result;
    }

    public function get_list_by_province($province){
        $result = 0;
       
        $text = "SELECT id_kabupaten, nama_kabupaten FROM $this->table 
            WHERE status = 1 AND id_provinsi = '$province' ORDER BY nama_kabupaten ASC";
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