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

	$namesub = clean_text($data ->{'namesub'});
    $idsub = (int)clean_text($data ->{'idsub'});
    $idpwc = (int)clean_text($data ->{'idpwc'});
    $idinv = (int)clean_text($data ->{'idinv'});
    $phone = clean_text($data ->{'phone'});
    $email = clean_text($data ->{'email'});
    $addsub = clean_text($data ->{'addsub'});
    $connection = clean_text($data ->{'connection'});
    $ipadd = clean_text($data ->{'ipadd'});
    $voltage = clean_text($data ->{'voltage'});
    $capacity = clean_text($data ->{'capacity'});
    $typesub = (int)clean_text($data ->{'typesub'});
    $gps = clean_text($data ->{'gps'});
    $mdms = clean_text($data ->{'mdms'});
    $startdate = clean_text($data ->{'startdate'});

    $sql = 'UPDATE `substation_power` SET `id_pwc`='.$idpwc.',`id_investor`='.$idinv.',`name_sub`="'.$namesub.'",
    		`phone_sub`="'.$phone.'",`email_sub`="'.$email.'",`address_sub`="'.$addsub.'",`connection_sub`='.$connection.',
    		`ip_address`="'.$ipadd.'",`levelvoltage`="'.$voltage.'",`levelcapacity`="'.$capacity.'",
    		`type_sub`='.$typesub.',`gps_sub`="'.$gps.'",`status`='.$mdms.',`startdate`="'.$startdate.'" 
    		WHERE `id_sub`='.$idsub;
    
    // $sql = 'UPDATE `investor` SET `name_investor`="'.$name.'",`phone_investor`="'.$phone.'",
    // 		`email_investor`="'.$email.'",`adress_investor`="'.$address.'" 
    // 		WHERE `id_investor`='.$invid;
	
	$result = mysql_query($sql) or die(json_encode($returnarray));

	$returnarray = array(
		'status' => 200,
		'content'=>	'Update info succesfully!'
	);

	echo json_encode($returnarray);	

	CLOSE_DB();
	unset($sql, $result);
          
?> 