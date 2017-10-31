<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$version = "v1.0";

	require_once($global['root-url-model']."/Connection.php");
	$obj_connect = new Connection();
	
	require_once($global['root-url-model'].$version."/Reference.php");
	$obj_reference = new Reference();

	//===================================== get reference ========================================
	//start get reference
	if($_GET['action'] == 'get_reference' && isset($_REQUEST['caption'])){
		$obj_connect->up();	
		$R_message = array("status" => "400", "message" => "No Data");

		$N_caption = mysql_real_escape_string($_REQUEST['caption']);

		$result = $obj_reference->get_list_by_caption($N_caption);
		if(is_array($result)){
			$R_message = array("status" => "200", "message" => "Data Exist", "data" => $result);
		}

		$obj_connect->down();	
		echo json_encode($R_message);	
	}//end get reference

	else{
		$R_message = array("status" => "404", "message" => "Action Not Found");
		echo json_encode($R_message);
	}

} else{
	$R_message = array("status" => "404", "message" => "Not Found");
	echo json_encode($R_message);
}
?>