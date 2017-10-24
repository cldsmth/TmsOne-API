<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$headers = getAllHeaders();
	$authorization = isset($headers['Authorization']) ? $headers['Authorization'] : "";

	if($headers['Accept'] == "application/json"){

		if(isAuthorize($authorization, $access_token['api-property'])){

			$version = "v1.0";

			require_once($global['root-url-model']."/Connection.php");
			$obj_connect = new Connection();
			
			require_once($global['root-url-model'].$version."/Property.php");
			$obj_property = new Property();

			require_once($global['root-url-model'].$version."/PropertyImage.php");
			$obj_image = new PropertyImage();

			require_once($global['root-url-model'].$version."/User.php");
			$obj_user = new User();

			//===================================== post ========================================
			//start post
			if($_GET['action'] == 'post' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){
				$obj_connect->up();
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);

				//field
				$N_token = mysql_real_escape_string($_REQUEST['token']);
				$N_owner = mysql_real_escape_string($_REQUEST['owner']);
				$N_title = mysql_real_escape_string($_REQUEST['title']);
				$N_hak = mysql_real_escape_string($_REQUEST['hak']);
				$N_city = mysql_real_escape_string($_REQUEST['city']);
				$N_kecamatan = mysql_real_escape_string($_REQUEST['kecamatan']);
				$N_kelurahan = mysql_real_escape_string($_REQUEST['kelurahan']);
				$N_address = mysql_real_escape_string($_REQUEST['address']);
				$N_zip = mysql_real_escape_string($_REQUEST['zip']);
				$N_jual_beli = mysql_real_escape_string($_REQUEST['jual_beli']);
				$N_jenis = mysql_real_escape_string($_REQUEST['jenis']);
				$N_status_property = mysql_real_escape_string($_REQUEST['status_property']);
				$N_sertifikat = mysql_real_escape_string($_REQUEST['sertifikat']);
				$N_promo = mysql_real_escape_string($_REQUEST['promo']);
				$N_menghadap = mysql_real_escape_string($_REQUEST['menghadap']);
				$N_lebar_depan = mysql_real_escape_string($_REQUEST['lebar_depan']);
				$N_panjang_tanah = mysql_real_escape_string($_REQUEST['panjang_tanah']);
				$N_luas_tanah = mysql_real_escape_string($_REQUEST['luas_tanah']);
				$N_luas_bangunan = mysql_real_escape_string($_REQUEST['luas_bangunan']);
				$N_bed = mysql_real_escape_string($_REQUEST['bed']);
				$N_bed_plus = mysql_real_escape_string($_REQUEST['bed_plus']);
				$N_bath = mysql_real_escape_string($_REQUEST['bath']);
				$N_bath_plus = mysql_real_escape_string($_REQUEST['bath_plus']);
				$N_floor = mysql_real_escape_string($_REQUEST['floor']);
				$N_daya_listrik = mysql_real_escape_string($_REQUEST['daya_listrik']);
				$N_sumber_air = mysql_real_escape_string($_REQUEST['sumber_air']);
				$N_fasilitas = mysql_real_escape_string($_REQUEST['fasilitas']);
				$N_description = mysql_real_escape_string($_REQUEST['description']);
				$N_hashtag = mysql_real_escape_string($_REQUEST['hashtag']);
				$N_price = mysql_real_escape_string($_REQUEST['price']);
				$N_komisi = mysql_real_escape_string($_REQUEST['komisi']);
				$N_type = mysql_real_escape_string($_REQUEST['type']);
				$N_status = 0;
				$N_create_date = mysql_real_escape_string($_REQUEST['create_date']);

				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$check_exist = $obj_property->check_exist($N_token, $N_type);
					//var_dump($check_exist);
					if($check_exist == 0){
						$result = $obj_property->insert_data($N_user_id, $N_owner, $N_title, $N_hak, $N_city, $N_kecamatan, $N_kelurahan, 
							$N_address, $N_zip, $N_jual_beli, $N_jenis, $N_status_property, $N_sertifikat, $N_promo, $N_menghadap, $N_lebar_depan, 
							$N_panjang_tanah, $N_luas_tanah, $N_luas_bangunan, $N_bed, $N_bed_plus, $N_bath, $N_bath_plus, $N_floor, $N_daya_listrik, 
							$N_sumber_air, $N_fasilitas, $N_description, $N_hashtag, $N_price, $N_komisi, $N_type, $N_status, $N_create_date);
						//var_dump($result);
						if($result >= 1){
							//insert image
							/*require_once($global['root-url']."packages/SimpleImage.php"); // class simple image
							if(isset($_FILES['image']['name'])){
								for($i = 0; $i < count($_FILES['image']['name']); $i++){
									if(!empty($_FILES['image']['name'][$i])){
										$N_image_token = mysql_real_escape_string($_REQUEST['image_token'][$i]);
										$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
										$file_name = cleanSpace($_FILES['image']['name'][$i]);
										$file_ext = strtolower(end(explode('.', $file_name)));
										$file_size = $_FILES['image']['size'][$i];
										$file_tmp = $_FILES['image']['tmp_name'][$i];
										$ran = rand();
							    		$timestamp = time();
										
										if(in_array($file_ext, $allowed_ext) === true){
											if($file_size < 10044070){
												//save image in server
												$file_loc = $global['root-url']."uploads/property/".$timestamp.$ran.$file_name; 
												$file_locThmb = $global['root-url']."uploads/property-thmb/".$timestamp.$ran.$file_name;

												//save image in database
												$file_loc1 = "uploads/property/".$timestamp.$ran.$file_name; 
												$file_locThmb1 = "uploads/property-thmb/".$timestamp.$ran.$file_name;

												if(move_uploaded_file($file_tmp, $file_loc))
												{
													$image = new SimpleImage();
													$image->load($file_loc);
													$image->resize(200,200);
													$image->save($file_locThmb);
												}
												
												$obj_image->insert_data($N_image_token, $N_token, $file_loc1, $file_locThmb1, $N_status);
											}
										}
									}
								}
							}*/
							$R_message = array("status" => "201", "message" => "Insert Data Success");
							$R_message['data']['id'] = $result;
						}else{
							$R_message = array("status" => "400", "message" => "Insert Data Failed");
						}
					}else{
						$result = $obj_property->update_data($N_token, $N_owner, $N_title, $N_hak, $N_city, $N_kecamatan, $N_kelurahan, 
							$N_address, $N_zip, $N_jual_beli, $N_jenis, $N_status_property, $N_sertifikat, $N_promo, $N_menghadap, $N_lebar_depan, 
							$N_panjang_tanah, $N_luas_tanah, $N_luas_bangunan, $N_bed, $N_bed_plus, $N_bath, $N_bath_plus, $N_floor, $N_daya_listrik,
							$N_sumber_air, $N_fasilitas, $N_description, $N_hashtag, $N_price, $N_komisi, $N_type);
						//var_dump($result);
						if($result == 1){
							$R_message = array("status" => "200", "message" => "Update Data Success");
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
					$result = $obj_property->get_property_sync($N_user_id, $N_data);
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
					$result = $obj_property->delete_data($N_token, $global['root-url']);
					//var_dump($result);
					if($result >= 1){
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