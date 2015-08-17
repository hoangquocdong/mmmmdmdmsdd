<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$id_sub =  (int)isset($_REQUEST['id_sub'])? $_REQUEST['id_sub'] : 0;
$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';

$month = clean_text($month);
$token = clean_text($token);

die('đsfsdfd');
CONNECT_DB();
mysql_query("SET NAMES utf8");

    if (!checktoken($userid, $token)){die ('201');}


    /*
    * lấy thông tin investor
    */

    $sql = 'SELECT meter.serial_meter FROM meter WHERE meter.id_sub = '.$id_sub;
	
	$result = mysql_query($sql) or die('500');

	$listmeter = array();
 	while($row = mysql_fetch_array($result)){
		$listmeter[$row['serial_meter']] = array(
				'serial_meter' => $row['serial_meter'],
				'level_meter' => $row['level_meter'],
				'relation_meter' => $row['relation_meter'] 				
			);

		//array_push($data, $tmp);
		
	}

	echo '<pre>'; print_r($listmeter); exit();
	
	$sql = 'SELECT meter.serial_meter, meter.level_meter, h1_confirm.ID, h1_confirm.type, h1_confirm.sub_confirm, h1_confirm.month_confirm  FROM meter
    		INNER JOIN h1_confirm
    		ON meter.serial_meter = h1_confirm.meter_serial AND month_confirm = "'.$month.'"
			WHERE meter.id_sub = '.$id_sub;


   //  $sql = 'SELECT meter.serial_meter, meter.level_meter, h1_confirm.ID, h1_confirm.type, h1_confirm.sub_confirm FROM meter
   //  		INNER JOIN h1_confirm
   //  		ON meter.serial_meter = h1_confirm.meter_serial AND h1_confirm.month_confirm = "'.$month.'"
			// WHERE meter.id_sub = '.$id_sub;
	
	$result = mysql_query($sql) or die('500');

	$data = array();
 	while($row = mysql_fetch_array($result)){
		$data[$row['serial_meter']] = array(
				'serial_meter' => $row['serial_meter'],
				'level_meter' => $row['level_meter'],
				'h1confirm_id' => $row['ID'],
				'type' => $row['type'],
				'sub_confirm' => $row['sub_confirm']			
			);

		//array_push($data, $tmp);
		
	}
	$value =array();
	$max=sizeof($listmeter);
	for ($i=0;$i<$max;$i++){
		$tmp=array(
				'serial_meter' => $listmeter['serial_meter'],
				'level_meter' => $listmeter['level_meter'],
				'h1confirm_id' => isset($data[$listmeter['serial_meter']])? $data[$listmeter['serial_meter']]['h1confirm_id'],
				'type' => isset($data[$listmeter['serial_meter']]) ? $data[$listmeter['serial_meter']]['type'],
				'sub_confirm' => isset($data[$listmeter['serial_meter']]) ? $data[$listmeter['serial_meter']]['sub_confirm']
		);
	}
	echo json_encode($value);

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
