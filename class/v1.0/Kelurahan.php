<?php
class Kelurahan{

	private $table = "kelurahan";

    public function get_kelurahan_sync($last_updated){
        $result = 0;
        $cond = $last_updated != "" ? "WHERE (UNIX_TIMESTAMP(timestamp) * 1000) > 
            (UNIX_TIMESTAMP('$last_updated') * 1000)" : "";
       
        $text = "SELECT id_kelurahan, id_kecamatan, nama_kelurahan, kodepos, timestamp FROM $this->table $cond";
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