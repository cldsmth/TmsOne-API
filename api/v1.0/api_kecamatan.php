<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$version = "v1.0";

	require_once($global['root-url-model']."/Connection.php");
	$obj_connect = new Connection();
	
	require_once($global['root-url-model'].$version."/Kecamatan.php");
	$obj_kecamatan = new Kecamatan();

	//===================================== get kecamatan ========================================
	//start get kecamatan
	if($_GET['action'] == 'get_kecamatan' && isset($_REQUEST['city'])){
		$obj_connect->up();	
		$R_message = array("status" => "400", "message" => "No Data");

		$N_city = mysql_real_escape_string($_REQUEST['city']);

		$result = $obj_kecamatan->get_list_by_city($N_city);
		if(is_array($result)){
			$R_message = array("status" => "200", "message" => "Data Exist", "data" => $result);
		}

		$obj_connect->down();	
		echo json_encode($R_message);	
	}//end get kecamatan

	else{
		$R_message = array("status" => "404", "message" => "Action Not Found");
		echo json_encode($R_message);
	}

} else{
	$R_message = array("status" => "404", "message" => "Not Found");
	echo json_encode($R_message);
}
?>