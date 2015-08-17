<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$start = microtime(true);


$serial_meter =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

$st =  isset($_REQUEST['st'])? $_REQUEST['st'] : 0;

$et =  isset($_REQUEST['et'])? $_REQUEST['et'] : 0;

//$serial_meter = clean_text($serial_meter);


  CONNECT_DB();
    mysql_query("SET NAMES utf8");


    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */


	if ($st == 0){
		$sql ='SELECT `value_import`, `value_export`, `value_total`, `q1`, `q2`, `q3`, `q4`,`full_time`, `full_time_d`  
				FROM `profile_value` WHERE `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,100';
	} else {
		$sql ='SELECT `value_import`, `value_export`, `value_total`, `q1`, `q2`, `q3`, `q4`,`full_time`, `full_time_d`   
				FROM `profile_value` WHERE `full_time_d` >= ".$st." AND  `full_time_d` <= ".$et." AND `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,1';
	}


	$result = mysql_query($sql) or die('0');


	while($row = mysql_fetch_array($result)){


		$tmp = array(
				'full_time' => $row['full_time'],
				'value_import' => $row['value_import'], 
				'value_export' => $row['value_export'], 
				'value_total' => $row['value_total'],
				'q1' => $row['q1'],
				'q2' => $row['q2'], 
				'q3' => $row['q3'], 
				'q4' => $row['q4']

				
			);
		array_push($data,$tmp);
	}


	$rs = array(
		'dienap' => $du,
		'dongdien' => $di,
		'tanso' => $df,
		'dulieu' => $data
		);

	//echo $time_elapsed_us = microtime(true) - $start;

	echo json_encode($rs);
	//echo json_encode($tmp2);
	CLOSE_DB();

	$vars = array_keys(get_defined_vars());
	for ($i = 0; $i < sizeOf($vars); $i++) {
	    unset($$vars[$i]);
	}
	unset($vars,$i);
          
?> 
