<?php
class Kecamatan{

	private $table = "kecamatan";
    private $itemPerPage = 1000;

    public function get_kecamatan_sync($page=1, $last_updated){
        $result = 0;
        $cond = $last_updated != "" ? "WHERE (UNIX_TIMESTAMP(timestamp) * 1000) > 
            (UNIX_TIMESTAMP('$last_updated') * 1000)" : "";

        //get total data
        $text_total = "SELECT id_kecamatan FROM $this->table $cond";
        $query_total = mysql_query($text_total);
        $total_data = mysql_num_rows($query_total);
        $total_data = $total_data < 1 ? 0 : $total_data;

        //get total page
        $total_page = ceil($total_data / $this->itemPerPage);
        $limitBefore = $page <= 1 || $page == null ? 0 : ($page-1) * $this->itemPerPage;
       
        $text = "SELECT id_kecamatan, id_kabupaten, nama_kecamatan, timestamp 
            FROM $this->table $cond LIMIT $limitBefore, $this->itemPerPage";
        $query = mysql_query($text);
        if(mysql_num_rows($query) >= 1){
            $result = array();
            while($row = mysql_fetch_assoc($query)){
                $result[] = $row;
            }
        }
        if(is_array($result)){
            $result[0]['total_page'] = $total_page;
            $result[0]['total_data_all'] = $total_data;
            $result[0]['total_data'] = count($result);
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