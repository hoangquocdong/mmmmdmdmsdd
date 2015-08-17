<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$serial =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;


$serial = clean_text($serial);
$month = clean_text($month);
$token = clean_text($token);


// $datestring=$month.'-01 first day of last month';
// $dt=date_create($datestring);
// $lastmonth =  $dt->format('Y-m').''; 

// $lastmonth = trim($lastmonth);

/*
* chưa có token
*/

$get_comfirm_data_available = 0;

  CONNECT_DB();
    mysql_query("SET NAMES utf8");


	echo $sql = 'SELECT `date_confirm`, `dauky` FROM `h1_confirm` WHERE `month_confirm` = "'.$month.'" AND meter_serial= "'.$serial.'" ORDER BY `ID` DESC LIMIT 0,1';

	$result = mysql_query($sql) or die('1110');
	$index=0;
		    $value = array();
    $confirmdata = array();
    echo mysql_num_rows($result);
    if (mysql_num_rows($result) > 0){
	 	while($row = mysql_fetch_array($result)){

			

		    	echo $index;
		    	$index++;
		    	echo $row['dauky'].$row['date_confirm'];
				$confirmdata = array(
					'dauky' => $row['dauky'], 
					'date_confirm' => $row['date_confirm']
				);

				$value = $confirmdata;
		} 
		
    }
 

  //   echo mysql_num_rows($result);
  //   if (mysql_num_rows($result) > 0){

	 // 	while($row = mysql_fetch_array($result)){
		// 	$value = $row['dauky'];
		// } 
		
  //   }

	echo json_encode($value);

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
