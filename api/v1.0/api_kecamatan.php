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

	//===================================== get sync ========================================
	//start sync
	else if($_GET['action'] == 'sync' && isset($_REQUEST['last_updated'])){
		$obj_connect->up();	
		$R_message = array("status" => "400", "message" => "No Data");

		$N_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
		$N_last_updated = mysql_real_escape_string($_REQUEST['last_updated']);

		$long_local = strtotime($N_last_updated) * 1000;
		$long_server = strtotime($obj_kecamatan->get_last_updated()) * 1000;
		if($long_server > $long_local){
			$result = $obj_kecamatan->get_kecamatan_sync($N_page, $N_last_updated);
			//var_dump($result);
			if(is_array($result)){
				$itemperpage = 1000;
				$total_data = $result[0]['total_data_all'];
				$remaining = $total_data - ((($N_page-1) * $itemperpage) + count($result));
				$R_message = array("status" => "200", "message" => "Data Exist", "num_data" => count($result), "remaining" => $remaining, "data" => $result);
			}
		}

		$obj_connect->down();	
		echo json_encode($R_message);	
	}//end sync

	else{
		$R_message = array("status" => "404", "message" => "Action Not Found");
		echo json_encode($R_message);
	}

} else{
	$R_message = array("status" => "404", "message" => "Not Found");
	echo json_encode($R_message);
}
?>