<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$headers = getAllHeaders();
	$authorization = isset($headers['Authorization']) ? $headers['Authorization'] : "";

	if($headers['Accept'] == "application/json"){

		if(isAuthorize($authorization, $access_token['api-tools'])){

			$version = "v1.0";

			require_once($global['root-url-model']."/Connection.php");
			$obj_connect = new Connection();
		
			require_once($global['root-url-model'].$version."/User.php");
			$obj_user = new User();

			//===================================== tools information ========================================
			//start get_data
			if($_GET['action'] == 'get_data' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){//START get_data
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "No Data");
				
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);
				
				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$file_json = $global['root-url']."uploads/json/api/tools.json";
					if(file_exists($file_json)){
				        $result = json_decode(file_get_contents($file_json), TRUE);
				        if(is_array($result)){
				            $R_message = array("status" => "200", "message" => "Data Exist", "data" => $result);
				        }
				    }
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();	
				echo json_encode($R_message);	
			}//end get_tools

			else{
				$R_message = array("status" => "404", "message" => "Action Not Found");
				echo json_encode($R_message);
			}

		}else{
			$R_message = array("status" => "401", "message" => "Access denied due to unauthorized process, please check access token api");
			echo json_encode($R_message);
		}

	}else{
		$R_message = array("status" => "403", "message" => "Invalid JSON format or header");
		echo json_encode($R_message);
	}

} else{
	$R_message = array("status" => "404", "message" => "Not Found");
	echo json_encode($R_message);
}
?>