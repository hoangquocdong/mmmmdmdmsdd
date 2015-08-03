<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$password = isset($_REQUEST['password'])? $_REQUEST['password'] : '';
$newpassword = isset($_REQUEST['newpassword'])? $_REQUEST['newpassword'] : '';

$password = clean_text($password);
$newpassword = clean_text($newpassword);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	
	$sql  =	'SELECT `action`, `datetime` FROM `newuserlogs` 
			WHERE userid = '.$userid.' ORDER BY ID DESC LIMIT 18';

	$result = mysql_query($sql) or die('500');

	$value=array();
	$value['status'] = '404';
	$value['result'] = array();
	while($rs=mysql_fetch_array($result)){
		$tmp = array(
				'action' => $rs['action'],
				'datetime' => $rs['datetime']
			);
		array_push($value['result'], $tmp);
		$value['status'] = '200';
    }

	echo json_encode($value);    

	CLOSE_DB();
	unset($sql, $result, $rs, $rows);
          
?> 