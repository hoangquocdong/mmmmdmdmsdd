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

$time_now=strtotime(date("m/d/Y H:i:s")); 	echo ('-1--'.$time_now);
$time_prev=$time_now-1*86400;echo ('--2-'.$time_prev);
$today = date("m/d/Y H:i:s");
$yesterday = date("m/d/Y H:i:s", $time_prev);
$mocthoigiandau=isset($_REQUEST['mocthoigiandau'])? (int)$_REQUEST['mocthoigiandau'] : $time_now;
$mocthoigiancuoi=isset($_REQUEST['mocthoigiancuoi'])? (int)$_REQUEST['mocthoigiancuoi'] : $time_prev;
	echo ('-3--'.$mocthoigiandau.'--');
  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	$data_first = array();
	$data_second = array();
	$rangeFrom1 = $mocthoigiandau - 120; 	echo ('-4--'.$rangeFrom1.'--');
	$rangeTo1 = $mocthoigiandau + 2419200; echo ('-4--'.$rangeTo1.'--');
	$rangeFrom2 = $mocthoigiancuoi - 120;
	$rangeTo2 = $mocthoigiancuoi + 2419200;	
	//echo ($mocthoigiandau);

    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */
	
	if ($st == 0){
		$sql1 ='SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
				`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
				FROM `history_value` WHERE `serial_meter` = "'.$serial_meter.'" AND full_time_d > $rangeFrom1 AND full_time_d < $rangeTo1 ORDER BY full_time_d ASC LIMIT 0, 1';
	
	} else {
		$sql ='SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
				`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
				FROM `history_value` WHERE `full_time_d` >= ".$st." AND  `full_time_d` <= ".$et." AND `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,1';
	}
	$result1 = mysql_query($sql1) or die('0');

	while($row = mysql_fetch_array($result1)){
		$tmp = array(
				'full_time' => $row['full_time'],
				'import_kw' => $row['import_kw'], 
				'rate1' => $row['rate1'], 
				'rate2' => $row['rate2'],
				'rate3' => $row['rate3'],
				'cd1' => $row['cd1'],				
				'q1' => $row['q1'], 
				'q2' => $row['q2'], 

				'export_kw' => $row['export_kw'], 
				'rate4' => $row['rate4'],
				'rate5' => $row['rate5'],
				'rate6' => $row['rate6'],
				'cd2' => $row['cd2'],				
				'q3' => $row['q3'], 
				'q4' => $row['q4']				
			);
		array_push($data1,$tmp);
	}


	$rs = array(
		'dauky' => $data1
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
