<?php
error_reporting(E_ALL ^ E_DEPRECATED);
// error_reporting(E_ALL);
// ini_set('display_errors', '1'); 
if (!isset($_SESSION)) {
  session_start();
}
include("class.phpmailer.php");
require_once("front_config.php");
require_once("check_input.php");
?>