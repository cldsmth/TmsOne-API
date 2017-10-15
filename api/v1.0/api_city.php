<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$version = "v1.0";

	require_once($global['root-url-model']."/Connection.php");
	$obj_connect = new Connection();
	
	require_once($global['root-url-model'].$version."/City.php");
	$obj_city = new City();

	//===================================== get city ========================================
	//start get city
	if($_GET['action'] == 'get_city' && isset($_REQUEST['province'])){
		$obj_connect->up();	
		$R_message = array("status" => "400", "message" => "No Data");

		$N_province = mysql_real_escape_string($_REQUEST['province']);

		$result = $obj_city->get_list_by_province($N_province);
		if(is_array($result)){
			$R_message = array("status" => "200", "message" => "Data Exist", "data" => $result);
		}

		$obj_connect->down();	
		echo json_encode($R_message);	
	}//end get city

	else{
		$R_message = array("status" => "404", "message" => "Action Not Found");
		echo json_encode($R_message);
	}

} else{
	$R_message = array("status" => "404", "message" => "Not Found");
	echo json_encode($R_message);
}
?>