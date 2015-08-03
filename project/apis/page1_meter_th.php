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

CONNECT_DB();
mysql_query("SET NAMES utf8");

    if (!checktoken($userid, $token)){die ('201');}

    /*
    * lấy thông tin investor
    */

    $sql = 'SELECT `serial_meter`, `level_meter`, `relation_meter`
    		FROM `meter` 
    		WHERE `id_sub` = '.$id_sub;
	
	$result = mysql_query($sql) or die('500');

	$listmeter = array();
 	while($row = mysql_fetch_array($result)){
		$listmeter[$row['serial_meter']] = array(
			'serial_meter' => $row['serial_meter'],
			'level_meter' => $row['level_meter'],
			'relation_meter' => $row['relation_meter'] 				
		);
	}

	//echo '<pre>'; print_r($listmeter); exit();
	
	$sql = 'SELECT meter.serial_meter, meter.level_meter, h1_confirm.ID, h1_confirm.type, h1_confirm.sub_confirm, h1_confirm.pc_confirm, h1_confirm.month_confirm  FROM meter
    		INNER JOIN h1_confirm
    		ON meter.serial_meter = h1_confirm.meter_serial AND month_confirm = "'.$month.'"
			WHERE meter.id_sub = '.$id_sub;


	$result = mysql_query($sql) or die('500');

	$data = array();

 	while($row = mysql_fetch_array($result)){
		$tmp = array(
				'serial_meter' => $row['serial_meter'],
				'level_meter' => $row['level_meter'],
				'h1confirm_id' => $row['ID'],
				'type' => $row['type'],
				'sub_confirm' => $row['sub_confirm'],
				'pc_confirm' => $row['pc_confirm']				
			);

		array_push($data, $tmp);
		
	}

	$val =array();
	
	foreach($listmeter as $key=>$value) { 
		$ccount=0;
		foreach($data as $key1=>$value1) { 
			//echo '<pre>'; print_r($value1); 
			if ($value1['serial_meter']=="$key"){

				//echo $value1[$key];

					$tmp=array(
						'serial_meter' => $value1['serial_meter'],
						'level_meter' =>  $value1['level_meter'],
						'relation_meter' =>  $value['relation_meter'],
						'h1confirm_id' => $value1['h1confirm_id'],
						'type' => $value1['type'],
						'sub_confirm' => $value1['sub_confirm'],
						'pc_confirm' => $value1['pc_confirm']	
					);	
					array_push($val,$tmp);

					$ccount++;
				
			} 

			//echo $value[][$key];
		}	

		if ($ccount==0) {
			$tmp=array(
				'serial_meter' => $value['serial_meter'],
				'level_meter' =>  $value['level_meter'],
				'relation_meter' =>  $value['relation_meter'],
				'h1confirm_id' => 0,
				'type' => 0,
				'sub_confirm' => 0,
				'pc_confirm' => 0
			);	
			array_push($val,$tmp);
		}
	}
	
	echo json_encode($val);

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
