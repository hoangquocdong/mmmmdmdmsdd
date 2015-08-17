<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id =  isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$value =  isset($_REQUEST['value'])? $_REQUEST['value'] : '';

$start = microtime(true);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

	
	$sql = 'SELECT `ID`, `dryseason_high`,`dryseason_normal`,`dryseason_low`,`dryseason_3area_price`,`rainyseason_high`,`rainyseason_normal`,
	`rainyseason_low`,`power_outof`,`year`, `area`  FROM price_seasons';
	$result = mysql_query($sql) or die('0');
	$data = array();
	$n = array();
	$m = array();
	$s = array();
	
	while($row = mysql_fetch_array($result)){
		$dryseason_normal_total= $row['dryseason_high'] + $row['dryseason_3area_price'];

		$tmp = array(
			'ID' => $row['ID'],
			'dryseason_high' =>(int) $row['dryseason_high'],
			'dryseason_normal' => (int)$row['dryseason_normal'],			
			'dryseason_low' => (int)$row['dryseason_low'],
			'dryseason_3area_price' => (int)$row['dryseason_3area_price'],
			'dryseason_normal_total' => $dryseason_normal_total,				
			'rainyseason_high' => (int)$row['rainyseason_high'],			
			'rainyseason_normal' => (int)$row['rainyseason_normal'],
			'rainyseason_low' => (int)$row['rainyseason_low'],			
			'power_outof' => (int)$row['power_outof'],
			'year' => $row['year'],
			'area' => $row['area']
		);
		if ($row['area']=="north") {$data['north']= $tmp;}
		if ($row['area']=="south") {$data['south']= $tmp;}
		if ($row['area']=="middle") {$data['middle']= $tmp;}			
	}

	
	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result);
          
?> 