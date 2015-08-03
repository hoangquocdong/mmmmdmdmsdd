<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$start = microtime(true);


$today=date("d-m-Y");

$serial_meter =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

// $date =  isset($_REQUEST['date'])? $_REQUEST['date'] : '';

$st =  isset($_REQUEST['st'])? $_REQUEST['st'] : '';
$et =  isset($_REQUEST['et'])? $_REQUEST['et'] : '';

//$st = $et = 0;
$default = 1;

if ($st==0||$st==''){ 
	$st=(int)(''.(strtotime($today)));
	$et = (int)$st+86400;
} else {
	$st = date($st);
	$st=(int)(''.(strtotime($st)));
	$et = date($et);
	$et=(int)(''.(strtotime($et)));
}



//echo $st.'--'.$et; exit();

$serial_meter = clean_text($serial_meter);


CONNECT_DB();
mysql_query("SET NAMES utf8");

    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */
    $data = array();
	
	$sql ='SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
			`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
			FROM `current_value` WHERE `full_time_d` > '.($st-60*10).' AND `full_time_d` < '.($et-60*10).' AND `serial_meter` = "'.$serial_meter.'" ORDER BY ID ASC LIMIT 0,1';

	$result = mysql_query($sql) or die('0');


	
	while($row = mysql_fetch_array($result)){

		$tmp = array(
				'full_time' => $row['full_time'],
				'import_kw' => $row['import_kw'], 
				'rate1' => $row['rate1'], 
				'rate2' => $row['rate2'],
				'rate3' => $row['rate3'],
				'q1' => $row['q1'], 
				'q2' => $row['q2'], 
				'cd1' => $row['cd1'],
				'export_kw' => $row['export_kw'], 
				'rate4' => $row['rate4'],
				'rate5' => $row['rate5'],
				'rate6' => $row['rate6'], 
				'q3' => $row['q3'], 
				'q4' => $row['q4'], 
				'cd2' => $row['cd2']				
			);
		$data['daungay']=$tmp;
	}

	//echo '<pre>'; print_r($data['daungay']);
	$sql ='SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
			`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
			FROM `current_value` WHERE `full_time_d` > '.($st+60*10).' AND `full_time_d` < '.($et+60*10).' AND `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,1';

	$result = mysql_query($sql) or die('0');

	
	while($row = mysql_fetch_array($result)){

		$tmp = array(
				'full_time' => $row['full_time'],
				'import_kw' => $row['import_kw'], 
				'rate1' => $row['rate1'], 
				'rate2' => $row['rate2'],
				'rate3' => $row['rate3'],
				'q1' => $row['q1'], 
				'q2' => $row['q2'], 
				'cd1' => $row['cd1'],
				'export_kw' => $row['export_kw'], 
				'rate4' => $row['rate4'],
				'rate5' => $row['rate5'],
				'rate6' => $row['rate6'], 
				'q3' => $row['q3'], 
				'q4' => $row['q4'], 
				'cd2' => $row['cd2']				
			);
		$data['cuoingay']=$tmp;
	}
	//echo '<pre>'; print_r($data['cuoingay']);
	//echo $time_elapsed_us = microtime(true) - $start;

	echo json_encode($data);
	//echo json_encode($tmp2);
	CLOSE_DB();

	$vars = array_keys(get_defined_vars());
	for ($i = 0; $i < sizeOf($vars); $i++) {
	    unset($$vars[$i]);
	}
	unset($vars,$i);
          
?> 
