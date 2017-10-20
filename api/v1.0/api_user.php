<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$headers = getAllHeaders();
	$authorization = isset($headers['Authorization']) ? $headers['Authorization'] : "";

	if($headers['Accept'] == "application/json"){

		if(isAuthorize($authorization, $access_token['api-user'])){

			$version = "v1.0";

			require_once($global['root-url-model']."/Connection.php");
			$obj_connect = new Connection();
			
			require_once($global['root-url-model']."/Encryption.php");
			$obj_encrypt = new Encryption();

			require_once($global['root-url-model'].$version."/User.php");
			$obj_user = new User();

			require_once($global['root-url-model'].$version."/Mail.php");
			$obj_mail = new Mail();

			//===================================== login ========================================
			//start login
			if($_GET['action'] == 'login' && isset($_POST['email']) && isset($_POST['password'])){
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "Login Failed");
				
				$N_email = mysql_real_escape_string($_POST['email']);
				$N_password = mysql_real_escape_string($_POST['password']);
				$password = md5($N_password);

				$result = $obj_user->login($N_email, $password);
				//var_dump($result);
				if(is_array($result)){
					$R_message = array("status" => "200", "message" => "Login Success", "data" => $result);
				}

				$obj_connect->down();
				echo json_encode($R_message);	
			}//end login

			//===================================== forget password ========================================
			//start forget password
			else if($_GET['action'] == 'forget_password' && isset($_POST['email'])){
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "Forget Password Failed");
				
				$N_email = mysql_real_escape_string($_POST['email']);

		        $result = $obj_user->get_forget_password($N_email);
				if(is_array($result)){
					$subject = "Forget Password TMS One";
					$message_html = $obj_mail->mail_forget_password($result[0]['user_name'], $obj_encrypt->decode($result[0]['user_password']));
					$mail = smtpmailer($N_email, $smtp['url'], $smtp['from'], $smtp['password'], $seo['company-name'], $subject, $message_html);
					if($mail){
						$R_message = array("status" => "200", "message" => "Forget password Success");
					}
				}

				$obj_connect->down();
				echo json_encode($R_message);	
			}//end forget password

			//===================================== update data ========================================
			//start update data
			else if($_GET['action'] == 'update_data' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "Edit profile failed");
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);

				$N_name = mysql_real_escape_string($_REQUEST['name']);
				$N_card_name = mysql_real_escape_string($_REQUEST['card_name']);
				$N_province = mysql_real_escape_string($_REQUEST['province']);
				$N_city = mysql_real_escape_string($_REQUEST['city']);
				$N_height = mysql_real_escape_string($_REQUEST['height']);
				$N_weight = mysql_real_escape_string($_REQUEST['weight']);
				$N_size = mysql_real_escape_string($_REQUEST['size']);
				$N_work = mysql_real_escape_string($_REQUEST['work']);
				$N_tempat_lahir = mysql_real_escape_string($_REQUEST['tempat_lahir']);
				$N_birthday = mysql_real_escape_string($_REQUEST['birthday']);
				$N_gender = mysql_real_escape_string($_REQUEST['gender']);
				$N_status_menikah = mysql_real_escape_string($_REQUEST['status_menikah']);
				$N_warga_negara = mysql_real_escape_string($_REQUEST['warga_negara']);
				$N_address = mysql_real_escape_string($_REQUEST['address']);
				$N_mail_address = mysql_real_escape_string($_REQUEST['mail_address']);
				$N_religion = mysql_real_escape_string($_REQUEST['religion']);
				$N_phone1 = mysql_real_escape_string($_REQUEST['phone1']);
				$N_phone2 = mysql_real_escape_string($_REQUEST['phone2']);
				$N_phone3 = mysql_real_escape_string($_REQUEST['phone3']);
				$N_npwp = mysql_real_escape_string($_REQUEST['npwp']);
				$N_instagram = mysql_real_escape_string($_REQUEST['instagram']);
				$N_whatsapp = mysql_real_escape_string($_REQUEST['whatsapp']);
				$N_bank = mysql_real_escape_string($_REQUEST['bank']);
				$N_cabang = mysql_real_escape_string($_REQUEST['cabang']);
				$N_no_rek = mysql_real_escape_string($_REQUEST['no_rek']);
				$image_loc1 = "";
				$image_locThmb1 = "";
				$scan_loc1 = "";
				$scan_locThmb1 = "";

				require_once($global['root-url']."packages/SimpleImage.php"); // class simple image
				if(isset($_FILES['image']['name'])){
					if(!empty($_FILES['image']['name'])){
						$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
						$file_name = cleanSpace($_FILES['image']['name']);
						$file_ext = strtolower(end(explode('.', $file_name)));
						$file_size = $_FILES['image']['size'];
						$file_tmp = $_FILES['image']['tmp_name'];
						$ran = rand();
			    		$timestamp = time();
						
						if(in_array($file_ext, $allowed_ext) === true){
							if($file_size < 10044070){
								//save image in server
								$image_loc = $global['root-url']."uploads/user/".$timestamp.$ran.$file_name; 
								$image_locThmb = $global['root-url']."uploads/user-thmb/".$timestamp.$ran.$file_name;

								//save image in database
								$image_loc1 = "uploads/user/".$timestamp.$ran.$file_name; 
								$image_locThmb1 = "uploads/user-thmb/".$timestamp.$ran.$file_name;

								if(move_uploaded_file($file_tmp, $image_loc))
								{
									$image = new SimpleImage();
									$image->load($image_loc);
									$image->resize(200,200);
									$image->save($image_locThmb);
								}
							}
						}
					}
				}

				if(isset($_FILES['scan']['name'])){
					if(!empty($_FILES['scan']['name'])){
						$allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
						$file_name = cleanSpace($_FILES['scan']['name']);
						$file_ext = strtolower(end(explode('.', $file_name)));
						$file_size = $_FILES['scan']['size'];
						$file_tmp = $_FILES['scan']['tmp_name'];
						$ran = rand();
			    		$timestamp = time();
						
						if(in_array($file_ext, $allowed_ext) === true){
							if($file_size < 10044070){
								//save image in server
								$scan_loc = $global['root-url']."uploads/user/".$timestamp.$ran.$file_name; 
								$scan_locThmb = $global['root-url']."uploads/user-thmb/".$timestamp.$ran.$file_name;

								//save image in database
								$scan_loc1 = "uploads/user/".$timestamp.$ran.$file_name; 
								$scan_locThmb1 = "uploads/user-thmb/".$timestamp.$ran.$file_name;

								if(move_uploaded_file($file_tmp, $scan_loc))
								{
									$image = new SimpleImage();
									$image->load($scan_loc);
									$image->resize(200,200);
									$image->save($scan_locThmb);
								}
							}
						}
					}
				}

				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$result = $obj_user->update_data($N_user_id, $N_name, $N_card_name, $N_province, $N_city, $N_height, $N_weight, $N_size, $N_work, $N_tempat_lahir, $N_birthday, $N_gender, $N_status_menikah, $N_warga_negara, $N_address, $N_mail_address, $N_religion, $N_phone1, $N_phone2, $N_phone3, $N_npwp, $N_instagram, $N_whatsapp, $N_bank, $N_cabang, $N_no_rek, $image_loc1, $image_locThmb1, $scan_loc1, $scan_locThmb1, $global['root-url']);
					if($result == 1){
						$R_message = array("status" => "200", "message" => "Edit profile success");
					}
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();
				echo json_encode($R_message);	
			}//end update data

			//===================================== change password ========================================
			//start change password
			else if($_GET['action'] == 'change_password' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){
				$obj_connect->up();	
				$R_message = array("status" => "404", "message" => "Change password failed");
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);

				$N_old_password = mysql_real_escape_string($_REQUEST['old_password']);
				$N_new_password = mysql_real_escape_string($_REQUEST['new_password']);
				$N_confirm_password = mysql_real_escape_string($_REQUEST['confirm_password']);
				$old_password = md5($N_old_password);
				$new_password = md5($N_new_password);

				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$check_password = $obj_user->check_password($N_user_id, $old_password);
					if($check_password == 1){
						if($N_new_password == $N_confirm_password){
							$result = $obj_user->update_password($N_user_id, $old_password, $new_password);
							if($result == 1){
								$R_message = array("status" => "200", "message" => "Change password success");
							}
						}else{
							$R_message = array("status" => "401", "message" => "Confirm password doesn't match");
						}
					}else{
						$R_message = array("status" => "401", "message" => "Your old password doesn't match");
					}
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();
				echo json_encode($R_message);	
			}//end change password

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