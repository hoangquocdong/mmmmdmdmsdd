<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$id =  (int)isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$userid = (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$type = (int)isset($_REQUEST['type'])? $_REQUEST['type'] : 0;

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    //check_token($userid, $info);

    $sql = 'SELECT `deletable` FROM `useradmin2015` WHERE `ID` = '.$userid;

	$result = mysql_query($sql) or die(json_encode($returnarray));

	$deletable = 0;
	while($row = mysql_fetch_array($result)){
		$deletable = $row['deletable'];
	}

	$returnarray = array(
		'status' => 404,
		'content'=>	'Delete denied!'
	); 

	if ($deletable == 0) die (json_encode($returnarray));

	/*
	*
	*/
	
	$returnarray = array(
		'status' => 500,
		'content'=>	'Delete fail!'
	);

    $sql = '';

    if ($type == 1){
    	$sql = 'DELETE FROM `meter` WHERE `serial_meter` = '.$id;
    } else if ($type == 2){
    	$sql = 'DELETE FROM `tu` WHERE `serial_tu` = '.$id;
    } else if ($type == 3){
    	$sql = 'DELETE FROM `ti` WHERE `serial_ti` = '.$id;
    }

    //echo $sql;

	$result = mysql_query($sql) or die(json_encode($returnarray));

	$returnarray = array(
		'status' => 200,
		'content'=>	'Delete succesfully!'
	);

    $actioncode = 10; //delete substation profile
    $moredetail = 'delete substation : '.$id;
    useradminlogs($userid, $actioncode, $moredetail);

	echo json_encode($returnarray);	

	CLOSE_DB();
	unset($sql, $result);
          
?> 