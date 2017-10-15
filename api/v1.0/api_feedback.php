<?php
header("content-type: application/json");
header("access-control-allow-origin: *");
require_once(dirname(__FILE__)."/../../packages/require.php");

if(isset($_GET['action'])){

	$headers = getAllHeaders();
	$authorization = isset($headers['Authorization']) ? $headers['Authorization'] : "";

	if($headers['Accept'] == "application/json"){

		if(isAuthorize($authorization, $access_token['api-feedback'])){

			$version = "v1.0";

			require_once($global['root-url-model']."/Connection.php");
			$obj_connect = new Connection();

			require_once($global['root-url-model'].$version."/User.php");
			$obj_user = new User();

			require_once($global['root-url-model'].$version."/Mail.php");
			$obj_mail = new Mail();

			//===================================== send ========================================
			//start send
			if($_GET['action'] == 'send' && isset($_REQUEST['user_id']) && isset($_REQUEST['auth_token'])){
				$obj_connect->up();	
				$R_message = array("status" => "400", "message" => "Send feedback failed");
				$N_user_id = mysql_real_escape_string($_REQUEST['user_id']);
				$N_auth_token = mysql_real_escape_string($_REQUEST['auth_token']);

				$N_name = mysql_real_escape_string($_REQUEST['name']);
				$N_email = mysql_real_escape_string($_REQUEST['email']);
				$N_message = mysql_real_escape_string($_REQUEST['message']);

				if($obj_user->check_code($N_auth_token, $N_user_id)){//check code
					$subject = "Feedback - ".$N_name;
					$message_html = $obj_mail->mail_feedback($N_name, $N_email, $N_message);
					$mail = smtpmailer($smtp['to'], $smtp['url'], $smtp['from'], $smtp['password'], $seo['company-name'], $subject, $message_html);
					if($mail){
						$R_message = array("status" => "200", "message" => "Send feedback success");
					}
				}//check code
				else{
					$R_message = array("status" => "401", "message" => "Unauthorized");
				}

				$obj_connect->down();
				echo json_encode($R_message);	
			}//end forget password

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