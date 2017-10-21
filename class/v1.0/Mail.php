<?php
class Mail{

	public function mail_feedback($name, $email, $message){
        $result = "<!DOCTYPE html>
          	<html>
	      	<head>
	      	<title></title>
	      	</head>
	      	<body>
	      	<span style='font-size:12px;'><em>*This is a support message email from User</em></span>
	      	<br/><br/>
	      	<b>Name</b> : ".$name."
	      	<br/><br/>
	      	<b>E-mail</b> : ".$email."
	      	<br/><br/>                       
	      	<b>Message</b> : <br/>
	      	".str_replace('\r\n','<br/>', $message)."     
	      	<br/><br/>
	      	<em>*Do not reply to this e-mail.<br/><br/>Thank you!</em>
	      	</body>
	      	</html>";
        return $result;
    }
	
	/*public function mail_forget_password($name, $password){
		$result = " 
	        <!DOCTYPE html>
	        <html>
	        <head>
	        <title></title>
	        </head>
	        <body>
	        Dear ".$name."
	        <br/><br/>
	        Berikut password kamu di TMS One<br/>
	        <strike><b>".$password."</b></strike><br/>
	        <br/><br/>
	        Selamat.<br/>
	        Regards,
	        <br/><br/>
	        TMS One
	        <br/><br/>
	        <em>*Do not reply to this e-mail.<br/><br/>Thank you!</em>
	        </body>
	        </html>
        	";
        return $result;
	}*/

}
?>