<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$serial =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';

$serial = clean_text($serial);
$month = clean_text($month);

/*
* chưa có token
*/

$tmp = $month.'-01';
$et = strtotime($tmp)-300;

$datestring=$tmp.' first day of last month';
$dt=date_create($datestring);
$st = $dt->format('Y-m-d'); 
$st = strtotime($st)-300;
$data = array();

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	
	function getdecimalnumber($unitmeter, $number, $decimalcount){
		if ($unitmeter == 1){
			$number = $number/1000;
		}
		$pointer = 1;	
		for ($i=1; $i<=$decimalcount; $i++){
			$pointer=$pointer*10;
		}		
		$result = floor($number*$pointer)/$pointer;
		$result = number_format($result ,$decimalcount ,"," ,"." );
		return $result;
	}
		$unitmeter=0; $countpoint = 0; $factor_meter=0; $lineloss_meter=0;
    /*
    *	Lây các thông số unit, số sau dấu phẩy, hệ số nhân, hệ số tổn thất ĐZ của meter
    */
	$sql = 'SELECT `unit_meter`,`count_point_meter`,`factor_meter`,`lineloss_meter` FROM `meter` WHERE `serial_meter` = "'.$serial.'" ';
	$result = mysql_query($sql) or die('0');
 	while($row = mysql_fetch_array($result)){
		$unitmeter=(int)$row['unit_meter'];
		$countpoint = (int)$row['count_point_meter'];
		$factor_meter=(int)$row['factor_meter'];
		$lineloss_meter = (int)$row['lineloss_meter'];
		$tmp = array(
				'unit_meter' => $unitmeter, 
				'count_point_meter' => $countpoint,
				'factor_meter' => $factor_meter, 
				'lineloss_meter' => $lineloss_meter
			);
		$data['meter'] = $tmp;
	} 
	
    $sql =  'SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
				`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
				FROM `history_value` 
    		WHERE `full_time_d` > '.$st.' AND `serial_meter` = "'.$serial.'" 
    		ORDER BY `full_time_d` ASC LIMIT 0,1';

    $result = mysql_query($sql) or die('0');


	while($row = mysql_fetch_array($result)){
		$tmp = array(
				'import_kw' => getdecimalnumber($unitmeter,(float)$row['import_kw'],$countpoint),
				'rate1' => getdecimalnumber($unitmeter,(float)$row['rate1'],$countpoint),
				'rate2' => getdecimalnumber($unitmeter,(float)$row['rate2'],$countpoint),
				'rate3' => getdecimalnumber($unitmeter,(float)$row['rate3'],$countpoint),
				'q1' => getdecimalnumber($unitmeter,(float)$row['q1'],$countpoint),
				'q2' => getdecimalnumber($unitmeter,(float)$row['q2'],$countpoint),
				'cd1' => getdecimalnumber($unitmeter,(float)$row['cd1'],$countpoint),
				'export_kw' => getdecimalnumber($unitmeter,(float)$row['export_kw'],$countpoint),
				'rate4' => getdecimalnumber($unitmeter,(float)$row['rate4'],$countpoint),
				'rate5' => getdecimalnumber($unitmeter,(float)$row['rate5'],$countpoint),
				'rate6' => getdecimalnumber($unitmeter,(float)$row['rate6'],$countpoint),
				'q3' => getdecimalnumber($unitmeter,(float)$row['q3'],$countpoint),
				'q4' => getdecimalnumber($unitmeter,(float)$row['q4'],$countpoint),
				'cd2' => getdecimalnumber($unitmeter,(float)$row['cd2'],$countpoint),
				'full_time' => $row['full_time']				
			);
		$data['dauky'] = $tmp;
	}


	$sql =  'SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
				`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
				FROM `history_value` 
    		WHERE `full_time_d` > '.$et.' AND `serial_meter` = "'.$serial.'" 
    		ORDER BY `full_time_d` ASC LIMIT 0,1';

    $result = mysql_query($sql) or die('0');

	while($row = mysql_fetch_array($result)){
			$tmp = array(
				'import_kw' => getdecimalnumber($unitmeter,(float)$row['import_kw'],$countpoint),
				'rate1' => getdecimalnumber($unitmeter,(float)$row['rate1'],$countpoint),
				'rate2' => getdecimalnumber($unitmeter,(float)$row['rate2'],$countpoint),
				'rate3' => getdecimalnumber($unitmeter,(float)$row['rate3'],$countpoint),
				'q1' => getdecimalnumber($unitmeter,(float)$row['q1'],$countpoint),
				'q2' => getdecimalnumber($unitmeter,(float)$row['q2'],$countpoint),
				'cd1' => getdecimalnumber($unitmeter,(float)$row['cd1'],$countpoint),
				'export_kw' => getdecimalnumber($unitmeter,(float)$row['export_kw'],$countpoint),
				'rate4' => getdecimalnumber($unitmeter,(float)$row['rate4'],$countpoint),
				'rate5' => getdecimalnumber($unitmeter,(float)$row['rate5'],$countpoint),
				'rate6' => getdecimalnumber($unitmeter,(float)$row['rate6'],$countpoint),
				'q3' => getdecimalnumber($unitmeter,(float)$row['q3'],$countpoint),
				'q4' => getdecimalnumber($unitmeter,(float)$row['q4'],$countpoint),
				'cd2' => getdecimalnumber($unitmeter,(float)$row['cd2'],$countpoint),
				'full_time' => $row['full_time'],				
			);
		$data['cuoiky'] = $tmp;
	}
	

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
