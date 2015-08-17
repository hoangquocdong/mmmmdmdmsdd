<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$serial =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';
$userid =  isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$dauky =  isset($_REQUEST['dauky'])? $_REQUEST['dauky'] : '';
$cuoiky =  isset($_REQUEST['cuoiky'])? $_REQUEST['cuoiky'] : '';
$fullname =  isset($_REQUEST['fullname'])? $_REQUEST['fullname'] : '';


$fullname = clean_text($fullname);
$dauky = clean_text($dauky);
$cuoiky = clean_text($cuoiky);
$token = clean_text($token);
$serial = clean_text($serial);
$month = clean_text($month);
$userid = (int)(clean_text($userid));


//if (!checktoken($userid, $token)){die ('0')}
/*
* chưa có token
*/

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	

    function record_exist($serial, $month){

		$sql='SELECT * FROM `h1_confirm` WHERE `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'"';
		
		$result = mysql_query($sql) or die('0');

		$row = 0;

		while($rows=mysql_fetch_array($result)){
			$row = $rows['ID'];
		}
		if ($row ==  null||$row==''){
			$returnvalue = 0;
		} else {
			$returnvalue = 1;
		}

		return $returnvalue;
	}


	if (record_exist($serial, $month)) die('409');

	$sql = 'INSERT INTO `h1_confirm`(`meter_serial`, `month_confirm`, `dauky`, `cuoiky`, `id_user_confirm`, 
				`fullname_sub_confirm`,`date_confirm`) 
				VALUES ("'.$serial.'","'.$month.'","'.$dauky.'","'.$cuoiky.'",'.$userid.',"'.$fullname.'","'.$timenow.'")';

    $result = mysql_query($sql) or die('0');

    echo '200';

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
