<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : '';
$password = isset($_REQUEST['password'])? $_REQUEST['password'] : '';


$password = clean_text($password);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

	$sql='SELECT ID FROM user WHERE password = "'.MD5($password).'" AND `ID` = '.$userid.' AND enable = 1';

	$result=mysql_query($sql) or die('500');

	$rows = 0;
	
	if ($result=mysql_query($sql)){
		$rows = mysql_num_rows($result);
	}
	
	if ($rows == 1){ echo '200'; } 
		else { echo "404";}

	CLOSE_DB();
	unset($sql, $result, $rs, $rows);
          
?> 