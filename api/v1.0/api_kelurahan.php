<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$version = "v1.0";

	require_once($global['root-url-model']."/Connection.php");
	$obj_connect = new Connection();
	
	require_once($global['root-url-model'].$version."/Kelurahan.php");
	$obj_kelurahan = new Kelurahan();

	//===================================== get kelurahan ========================================
	//start get kelurahan
	if($_GET['action'] == 'get_kelurahan' && isset($_REQUEST['kecamatan'])){
		$obj_connect->up();	
		$R_message = array("status" => "400", "message" => "No Data");

		$N_kecamatan = mysql_real_escape_string($_REQUEST['kecamatan']);

		$result = $obj_kelurahan->get_list_by_kecamatan($N_kecamatan);
		if(is_array($result)){
			$R_message = array("status" => "200", "message" => "Data Exist", "data" => $result);
		}

		$obj_connect->down();	
		echo json_encode($R_message);	
	}//end get kelurahan

	else{
		$R_message = array("status" => "404", "message" => "Action Not Found");
		echo json_encode($R_message);
	}

} else{
	$R_message = array("status" => "404", "message" => "Not Found");
	echo json_encode($R_message);
}
?>