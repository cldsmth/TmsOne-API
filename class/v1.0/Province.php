<?php
class Province{

	private $table = "provinsi";

    public function get_province_sync($last_updated){
        $result = 0;
        $cond = $last_updated != "" ? "AND (UNIX_TIMESTAMP(timestamp) * 1000) > 
            (UNIX_TIMESTAMP('$last_updated') * 1000)" : "";
       
        $text = "SELECT id_provinsi, nama_provinsi, keterangan, status, timestamp 
            FROM $this->table WHERE status = 1 $cond ORDER BY nama_provinsi ASC";
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