<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$id =  (int)isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$password =  isset($_REQUEST['password'])? $_REQUEST['password'] : '';
$password = clean_text($password);

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    $returnarray = array(
		'status' => 500,
		'content'=>	'Update password fail!'
	);

	$sql = 'UPDATE `user` 
			SET `password`="'.MD5($password).'" WHERE `ID`='.$userid;

	$result = mysql_query($sql) or die(json_encode($returnarray));

	$returnarray = array(
		'status' => 200,
		'content'=>	'Update password succesfully!'
	);

	$actioncode = 20; //change user pass substation profile
    $moredetail = 'change pas userid : '.$id;
    useradminlogs($userid, $actioncode, $moredetail);

	echo json_encode($returnarray);	
	
	CLOSE_DB();
	unset($sql, $result);
          
?> 