<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$headers = getAllHeaders();
	$authorization = isset($headers['Authorization']) ? $headers['Authorization'] : "";

	if($headers['Accept'] == "application/json"){

		if(isAuthorize($authorization, $access_token['api-owner'])){

			$version = "v1.0";

			require_once($global['root-url-model']."/Connection.php");
			$obj_connect = new Connection();
			
			require_once($global['root-url-model'].$version."/Owner.php");
			$obj_owner = new Owner();

			require_once($global['root-url-model'].$version."/Property.php");
			$obj_property = new Property();

			require_once($global['root-url-model'].$version."/User.php");
			$obj_user = new User();

			//===================================== get owner ========================================
			//start get owner
			if($_GET['action'] == 'get_owner' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){//START get_owner
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "No Data");
				
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);
				$N_keyword = isset($_REQUEST['keyword']) ? $_REQUEST['keyword'] : "";
				$N_page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
				
				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$result = $obj_owner->get_owner($N_page, $N_keyword);
					//var_dump($result);
					if(is_array($result)){
						$itemperpage = 6;
						$total_data = $result[0]['total_data_all'];
						$remaining = $total_data - ((($N_page-1) * $itemperpage) + count($result));
						$R_message = array("status" => "200", "message" => "Data Exist", "num_data" => count($result), "remaining" => $remaining, "data" => $result);
					}
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();	
				echo json_encode($R_message);	
			}//end get owner

			//===================================== post ========================================
			//start post
			else if($_GET['action'] == 'post' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){
				$obj_connect->up();
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);

				//field
				$N_token = mysql_real_escape_string($_REQUEST['token']);
				$N_name = mysql_real_escape_string($_REQUEST['name']);
				$N_email = mysql_real_escape_string($_REQUEST['email']);
				$N_tempat_lahir = mysql_real_escape_string($_REQUEST['tempat_lahir']);
				$N_birthday = mysql_real_escape_string($_REQUEST['birthday']);
				$N_gender = mysql_real_escape_string($_REQUEST['gender']);
				$N_city = mysql_real_escape_string($_REQUEST['city']);
				$N_kecamatan = mysql_real_escape_string($_REQUEST['kecamatan']);
				$N_kelurahan = mysql_real_escape_string($_REQUEST['kelurahan']);
				$N_address = mysql_real_escape_string($_REQUEST['address']);
				$N_phone1 = mysql_real_escape_string($_REQUEST['phone1']);
				$N_phone2 = mysql_real_escape_string($_REQUEST['phone2']);
				$N_phone3 = mysql_real_escape_string($_REQUEST['phone3']);
				$N_ktp = mysql_real_escape_string($_REQUEST['ktp']);
				$N_status = 1;
				$N_create_date = mysql_real_escape_string($_REQUEST['create_date']);
				$image_name = "";

				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					if(isset($_FILES['image']['name'])){
						if(!empty($_FILES['image']['name'])){
							$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
							$file_name = $_FILES['image']['name'];
							$file_ext_tmp = explode('.', $file_name);
							$file_ext = strtolower(end($file_ext_tmp));
							$file_size = $_FILES['image']['size'];
							$file_tmp = $_FILES['image']['tmp_name'];
							
							if(in_array($file_ext, $allowed_ext) === true){
								if($file_size < 10044070){
									$image_name = "owner_".md5($file_name).".".$file_ext;
									$image_loc = $global['root-url-image']."owner_photo/".$image_name; 
									move_uploaded_file($file_tmp, $image_loc);
								}
							}
						}
					}

					$check_exist = $obj_owner->check_exist($N_token);
					//var_dump($check_exist);
					if($check_exist == 0){
						$result = $obj_owner->insert_data($N_user_id, $N_name, $N_email, $N_tempat_lahir, $N_birthday, $N_gender, $N_city, $N_kecamatan, $N_kelurahan, $N_address, $N_phone1, $N_phone2, $N_phone3, $N_ktp, $image_name, $N_status, $N_create_date);
						//var_dump($result);
						if($result >= 1){
							$R_message = array("status" => "201", "message" => "Insert Data Success");
							$R_message['data']['id'] = $result;
						}else{
							$R_message = array("status" => "400", "message" => "Insert Data Failed");
						}
					}else{
						$result = $obj_owner->update_data($N_token, $N_name, $N_email, $N_tempat_lahir, $N_birthday, $N_gender, $N_city, $N_kecamatan, $N_kelurahan, $N_address, $N_phone1, $N_phone2, $N_phone3, $N_ktp, $image_name, $global['root-url-image']);
						//var_dump($result);
						if($result == 1){
							$R_message = array("status" => "200", "message" => "Update Data Success");
							$R_message['data']['id'] = $N_token;
						}else{
							$R_message = array("status" => "400", "message" => "Update Data Failed");
						}
					}
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();
				echo json_encode($R_message);	
			}//end post

			//===================================== sync ========================================
			//start sync
			else if($_GET['action'] == 'sync' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){//START sync
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "No Data");
				
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);
				$N_data = isset($_REQUEST['data']) ? $_REQUEST['data'] : "";

				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$result = $obj_owner->get_owner_sync($N_user_id, $N_data);
					//var_dump($result);
					if(is_array($result)){
						$R_message = array("status" => "200", "message" => "Data Exist", "data" => $result);
					}
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();	
				echo json_encode($R_message);	
			}//end sync

			//===================================== delete ========================================
			//start delete
			else if($_GET['action'] == 'delete_data' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){//START delete
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "Delete failed");
				
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);
				$N_token = mysql_real_escape_string($_REQUEST['token']);

				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$result = $obj_owner->delete_data($N_token, $global['root-url-image']);
					//var_dump($result);
					if($result >= 1){
						$obj_property->delete_data_by_owner($N_token, "", $global['root-url-image']);
						$obj_property->delete_data_by_owner($N_token, "request", $global['root-url-image']);
						$R_message = array("status" => "200", "message" => "Delete success");
					}
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();	
				echo json_encode($R_message);	
			}//end delete

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