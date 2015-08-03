<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$info =  isset($_REQUEST['info'])? $_REQUEST['info'] : '';

$start = microtime(true);

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    $returnarray = array(
		'status' => 500,
		'content'=>	'Update info fail!'
	);

	$data = json_decode($info);

	$name = clean_text($data ->{'name'});
    $invid = (int)clean_text($data ->{'id'});
    $phone = clean_text($data ->{'phone'});
    $email = clean_text($data ->{'email'});
    $address = clean_text($data ->{'address'});

    $sql = 'UPDATE `investor` SET `name_investor`="'.$name.'",`phone_investor`="'.$phone.'",
    		`email_investor`="'.$email.'",`adress_investor`="'.$address.'" 
    		WHERE `id_investor`='.$invid;
	
	$result = mysql_query($sql) or die(json_encode($returnarray));

	$returnarray = array(
		'status' => 200,
		'content'=>	'Update info succesfully!'
	);

	echo json_encode($returnarray);	

	CLOSE_DB();
	unset($sql, $result);
          
?> 