<?php
class City{

	private $table = "t_city";
    private $joinJenis = "LEFT JOIN t_jenis jenis ON jenis.id_jenis = city.id_jenis";

    public function get_list_by_province($province){
        $result = 0;
       
        $text = "SELECT city.id_kab, city.nama, jenis.nama AS jenis 
            FROM $this->table city $this->joinJenis WHERE city.id_prov = '$province'";
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