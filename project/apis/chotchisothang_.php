<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "../libs/custom_functions.php";

$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$serial =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;


$serial = clean_text($serial);
$month = clean_text($month);
$token = clean_text($token);

/*
* chưa có token
*/

$data = array();
$data['status']=404;	//có tìm thấy dữ liệu trong bảng confirm ko? có thì ko cho ghi - ko thì cho xác nhận ol - code: 200
$data['type']=0;
$data['sourcedauky']='h1now';
$data['sourcecuoiky']='h1now';
$get_comfirm_data_available = 0;

$tmp = $month.'-01';
$dt=date_create($tmp);
$st = $dt->format('Y-m-d'); 
$st = strtotime($st)-300; 												//đầu tháng chọn in timespan format

$date = new DateTime($tmp);
//$date = date_create($tmp);
$nowTimestamp = $date->getTimestamp();
$date->modify('first day of next month');
$et = $date->getTimestamp()-300;										//cuối tháng chọn in timespan format

//$et = strtotime('first day of next month'))-300;

$datestring=$tmp.' first day of last month';
$dt=date_create($datestring);
$lastmon = $dt->format('Y-m-d'); 

$lastmonthstr = explode('-', $lastmon);
//echo '<pre>'; print_r($lastmonthstr);
$lmstr = (int)$lastmonthstr[0].'-'.(int)$lastmonthstr[1]; 				//tháng trước in Y-m format

//echo $st.' == '.$et.'  ==  '.$lmstr; exit();


  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $unitmeter=0; $countpoint = 3; $factor_meter=0; $lineloss_meter=0;

		$sql =  'SELECT `import_kw`, `export_kw`, `q1`, `q2`, `q3`, `q4`, `rate1`, `rate2`, 
					`rate3`, `rate4`, `rate5`, `rate6`, `cd1`, `cd2`,`full_time`, `full_time_d`  
				FROM `history_value` 
	    		WHERE `full_time_d` > '.$et.' AND `serial_meter` = "'.$serial.'" 
	    		ORDER BY `full_time_d` ASC LIMIT 0,1';

	    $result = mysql_query($sql) or die('0');

	    //$tmp=array();
	    $tmp = new stdClass();
		while($row = mysql_fetch_array($result)){

				$tmp ->{'rate1'} = (double)$row['rate1'];
				$tmp ->{'rate11'} = getdecimalnumber($unitmeter,(double)$row['rate1'],$countpoint);
				$tmp ->{'rate111'} = number_format((double)$row['rate1'], 3, ',', ' ');	
				

				// $tmp ->{'import_kw'} = getdecimalnumber($unitmeter,(double)$row['import_kw'],$countpoint);
				// $tmp ->{'rate1'} = getdecimalnumber($unitmeter,(double)$row['rate1'],$countpoint);
				// $tmp ->{'rate2'} = getdecimalnumber($unitmeter,(double)$row['rate2'],$countpoint);
				// $tmp ->{'rate3'} = getdecimalnumber($unitmeter,(double)$row['rate3'],$countpoint);
				// $tmp ->{'cd1'} = getdecimalnumber($unitmeter,(double)$row['cd1'],$countpoint);
				// $tmp ->{'q1'} = getdecimalnumber($unitmeter,(double)$row['q1'],$countpoint);
				// $tmp ->{'q2'} = getdecimalnumber($unitmeter,(double)$row['q2'],$countpoint);
					
				// $tmp ->{'export_kw'} = getdecimalnumber($unitmeter,(double)$row['export_kw'],$countpoint);
				// $tmp ->{'rate4'} = getdecimalnumber($unitmeter,(double)$row['rate4'],$countpoint);
				// $tmp ->{'rate5'} = getdecimalnumber($unitmeter,(double)$row['rate5'],$countpoint);
				// $tmp ->{'rate6'} = getdecimalnumber($unitmeter,(double)$row['rate6'],$countpoint);

				// $tmp ->{'cd2'} = getdecimalnumber($unitmeter,(double)$row['cd2'],$countpoint);
				// $tmp ->{'q3'} = getdecimalnumber($unitmeter,(double)$row['q3'],$countpoint);
				// $tmp ->{'q4'} = getdecimalnumber($unitmeter,(double)$row['q4'],$countpoint);
				// $tmp ->{'full_time'} = $row['full_time'];				
		}
		//print_r($tmp);

		$data['cuoiky'] = $tmp;

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs);
    
    function getdecimalnumber($unitmeter, $number, $decimalcount){
    	//echo $number."<br/>";

    	$number = 123.19;
		if ($unitmeter == 1){
			$number = $number/1000;
		}
		$pointer = 1;	
		for ($i=1; $i<=$decimalcount; $i++){
			$pointer=$pointer*10;
		}		
		echo $result = $number*$pointer.''; echo "<br/>";

		echo $result = (int)$result/$pointer; echo "<br/>";

		echo floor((int)($number*$pointer)).''; echo "<br/>";

		echo ($number*$pointer)/$pointer."<br/>";

		//$result = ($number*$pointer)/$pointer;
		//$result = floor((int)$number*$pointer)/$pointer;
		//$result = $number;
		$result = number_format($result ,$decimalcount ,"," ,"." );
		return $result;
	}      
?> 
