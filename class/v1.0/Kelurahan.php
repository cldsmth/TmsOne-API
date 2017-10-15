<?php
class Kelurahan{

	private $table = "t_kelurahan";
    private $joinJenis = "LEFT JOIN t_jenis jenis ON jenis.id_jenis = kelurahan.id_jenis";

    public function get_list_by_kecamatan($kecamatan){
        $result = 0;
       
        $text = "SELECT kelurahan.id_kel, kelurahan.nama, jenis.nama AS jenis 
            FROM $this->table kelurahan $this->joinJenis WHERE kelurahan.id_kec = '$kecamatan'";
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