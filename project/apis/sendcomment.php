<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "../libs/custom_functions.php";

$fullname =  isset($_REQUEST['fullname'])? $_REQUEST['fullname'] : '';
$email =  isset($_REQUEST['email'])? $_REQUEST['email'] : '';
$phone =  isset($_REQUEST['phone'])? $_REQUEST['phone'] : '';
$message =  isset($_REQUEST['message'])? $_REQUEST['message'] : '';
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;


$fullname = clean_text($fullname);
$email = clean_text($email);
$phone = clean_text($phone);
$message = clean_text($message);
$time = time();

CONNECT_DB();

mysql_query("SET NAMES utf8");

$checktoken = checktoken($userid, $token);

if (!$checktoken) die ('404');

//echo $fullname.$email.$phone.$message.$userid; exit();

$sql = 'INSERT INTO `ykienphanhoi`(`userid`, `fullname`, `email`, `phone`, `content`, `time`) 
		VALUES ('.$userid.',"'.$fullname.'","'.$email.'","'.$phone.'","'.$message.'",'.$time.')';

$result = mysql_query($sql) or die('500');

echo '200';

CLOSE_DB();
unset($sql, $result);
    
?> 
