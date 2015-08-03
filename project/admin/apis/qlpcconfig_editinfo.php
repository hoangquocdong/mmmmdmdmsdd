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

	$email = clean_text($data ->{'email'});
	$operator = clean_text($data ->{'operator'});
	$pcname = clean_text($data ->{'pcname'});
	$pcid = (int)clean_text($data ->{'pcid'});
	$phone = clean_text($data ->{'phone'});
	$status = (int)clean_text($data ->{'status'});
	$id_orderby = (int)clean_text($data ->{'pcidorderby'});

	$sql = 'UPDATE `power_company` SET `name_pwc`="'.$pcname.'",`name_operator_pc`="'.$operator.'",
			`phone_operator_pc`="'.$pcname.'",`email_operator_pc`="'.$pcname.'",`status`='.$status.',`id_orderby`='.$id_orderby.' 
			WHERE `id_pwc`='.$pcid;
	
	$result = mysql_query($sql) or die(json_encode($returnarray));

	$returnarray = array(
		'status' => 200,
		'content'=>	'Update info succesfully!'
	);

	echo json_encode($returnarray);	

	CLOSE_DB();
	unset($sql, $result);
          
?> 