<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$serial =  isset($_REQUEST['serial'])? $_REQUEST['serial'] : '';
$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$dauky =  isset($_REQUEST['dauky'])? $_REQUEST['dauky'] : '';
$cuoiky =  isset($_REQUEST['cuoiky'])? $_REQUEST['cuoiky'] : '';
$fullname =  isset($_REQUEST['fullname'])? $_REQUEST['fullname'] : '';
$kdstatus =  (int)isset($_REQUEST['kdstatus'])? $_REQUEST['kdstatus'] : 0;

$fullname = clean_text($fullname);
$dauky = clean_text($dauky);
$cuoiky = clean_text($cuoiky);
$token = clean_text($token);
$serial = clean_text($serial);
$month = clean_text($month);
//$userid = (int)(clean_text($userid));


//if (!checktoken($userid, $token)){die ('201')}
/*
* chưa có token
*/

  CONNECT_DB();

    mysql_query("SET NAMES utf8");
	
    if (!checktoken($userid, $token)){die ('201');}

    function record_exist($serial, $month, $kdstatus){

		$sql=	'SELECT `sub_confirm` FROM `h1_confirm` 
				WHERE `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'" AND `type` = 2';	//check skd <> type = 2
		
		$result = mysql_query($sql) or die('500');

		$returnvalue = mysql_affected_rows();

		return $returnvalue;
	}


	if (record_exist($serial, $month, $kdstatus)) die('409');

    /*
    *	Chus ý cả phần PC confirm cũng thay đổi
    */
	
    $val = 409;					//	Đã tồn tại

	$sql = 'UPDATE 	`h1_confirm` 
			SET `dauky`="'.$dauky.'",`cuoiky`="'.$cuoiky.'", `sub_confirm`= 1 ,
			`id_user_confirm`='.$userid.',`fullname_sub_confirm`="'.$fullname.'",`date_confirm`="'.$timenow.'"
			WHERE `meter_serial` ="'.$serial.'" AND `month_confirm` = "'.$month.'" AND `type` = 2';

    $result = mysql_query($sql) or die('500');

    $countaffectedrows = mysql_affected_rows();

    if ($countaffectedrows == 1){
    	$val = 200;	
    }
    echo $val;

	userlogs($userid , $fullname , 'xác nhận số liệu sau kiểm định công tơ');
	
	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
