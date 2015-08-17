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

$st =  isset($_REQUEST['st'])? $_REQUEST['st'] : '';

$et =  isset($_REQUEST['et'])? $_REQUEST['et'] : '';

$default = 1;
if ($st==''){ $st=(int)(''.(strtotime($today)));} 
	else { $st = (int)(''.(strtotime($st))); $default = 0;}


if ($et==''){ $et = (int)$st+86400;} 
	else { $et = (int)(''.(strtotime($et))); }

//echo $st.$et; exit();

//echo $st.' -- '.$et; exit();

$serial_meter = clean_text($serial_meter);


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
		return $result.'';
	}
	function convertarray($data){
		$length = sizeof($data)-1;
        $newarray='';
        for ($i=0; $i<=$length; $i++){
        $newarray[$i]=$data[$length-$i];        
        }
        return $newarray;
    }
	
	function shortarray($arr){
		$length = sizeof($arr)-1;

		for ($i = 0; $i <= $length; $i++)
		{
			for ($j = $i + 1; $j <= $length; $j++) // lặp các phần tử phía sau
			{
				if ($arr[$i]['full_time'] > $arr[$j]['full_time']) // nếu phần tử $i bé hơn phần tử phía sau
				{
					// hoán vị
					$tmp = $arr[$j];
					$arr[$j] = $arr[$i];
					$arr[$i] = $arr;
				}
			}
		}	
		
        return $arr;
	}
	
	$unitmeter=0; $countpoint = 0;
    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */
	$sql = 'SELECT `unit_meter`,`count_point_meter` FROM `meter` WHERE `serial_meter` = "'.$serial_meter.'" ';
	$result = mysql_query($sql) or die('0');
 	while($row = mysql_fetch_array($result)){
		$unitmeter=(int)$row['unit_meter'];
		$countpoint = (int)$row['count_point_meter'];
		$tmp = array(
				'unit_meter' => $unitmeter, 
				'count_point_meter' => $countpoint
			);
		$data['meter'] = $tmp;
	} 
    /*
    *	Lây dư liệu bảng - chỉ số công tơ
    */
    if ($default) {
    	$sql ='SELECT `value_import`, `value_export`, `value_total`, `q1`, `q2`, `q3`, `q4`,`full_time`, `full_time_d`  
    	FROM `profile_value` WHERE `serial_meter` = "'.$serial_meter.'" ORDER BY ID DESC LIMIT 0,350';
    } else {
    	$sql ='SELECT `value_import`, `value_export`, `value_total`, `q1`, `q2`, `q3`, `q4`,`full_time`, `full_time_d`   
			FROM `profile_value` WHERE `full_time_d` > '.$st.' AND  `full_time_d` <= '.$et.' AND `serial_meter` = "'.$serial_meter.'"';// ORDER BY ID DESC';
    }		//`full_time_d` BETWEEB '.$st.' AND '.$et.'
	
	$result = mysql_query($sql) or die('0');

	$data = array();
	$imp = array();
	$exp = array();
	while($row = mysql_fetch_array($result)){

		$fulltime = (int)$row['full_time_d']*1000;

		array_push($imp, array($fulltime,(float)$row['value_import']));
        array_push($exp, array($fulltime,(float)$row['value_export']));

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

	$du = array();
	$di = array();

	$newimp = array();
	foreach ($imp as $key => $row)
	{
	    $newimp[$key] = $row[0];
	}
	array_multisort($newimp, SORT_ASC, $imp);

	$newexp = array();
	foreach ($imp as $key => $row)
	{
	    $newexp[$key] = $row[0];
	}
	array_multisort($newexp, SORT_ASC, $exp);
	
	//$newdata = array();
	//foreach ($data as $key => $row)
	//{
	//    $newdata[$key] = strtotime($row['full_time']);
	// }
	//array_multisort($newdata, SORT_ASC, $data);	
	
	//if ($default) {
	//	$imp=convertarray($imp);
	//	$exp=convertarray($exp);
	//	$data=convertarray($data);
	//}



	array_push($du, $imp);
	array_push($di, $exp);

	$rs = array(
		'pgiao' => $du,
		'pnhan' => $di,
		'dulieu' => $data
		);
	
	//echo $time_elapsed_us = microtime(true) - $start;

	echo json_encode($rs);

	CLOSE_DB();

	$vars = array_keys(get_defined_vars());
	for ($i = 0; $i < sizeOf($vars); $i++) {
	    unset($$vars[$i]);
	}
	unset($vars,$i);
          
?> 
