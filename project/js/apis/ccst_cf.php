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


		$sql=	'SELECT `sub_confirm` FROM `h1_confirm` 
				WHERE `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'" AND `type` = '.$kdstatus;
		
		$result = mysql_query($sql) or die('500');

		$returnvalue = 0;

		while($rows=mysql_fetch_array($result)){
			$returnvalue = (int)$rows['sub_confirm'];
		}

		$returnvalue;


	if ($returnvalue) die('409');

	/*
	*	$kdstatus == 2 là sau kiểm định
	*/
	if ($kdstatus!=2){
		

		$sql = 'INSERT INTO `h1_confirm`(`meter_serial`, `month_confirm`, `dauky`, `cuoiky`, `type`, `sub_confirm`, `id_user_confirm`, 
					`fullname_sub_confirm`,`date_confirm`) 
					VALUES ("'.$serial.'","'.$month.'","'.$dauky.'","'.$cuoiky.'",'.$kdstatus.', 1,'.$userid.',"'.$fullname.'","'.$timenow.'")';

	    $result = mysql_query($sql) or die('500');

	    echo '200';

	} else {


		$sql = 'UPDATE `h1_confirm` SET `dauky`="'.$dauky.'",`cuoiky`="'.$cuoiky.'",`sub_confirm`=1,
				`id_user_confirm`='.$userid.',`fullname_sub_confirm`="'.$fullname.'",`date_confirm`="'.$timenow.'"
				WHERE `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'" AND `type` = '.$kdstatus;

		$result = mysql_query($sql) or die('500');

		if ( mysql_affected_rows()<=0) {

			$sql = 'INSERT INTO `h1_confirm`(`meter_serial`, `month_confirm`, `dauky`, `cuoiky`, `type`, `sub_confirm`,
				 `id_user_confirm`, 
				`fullname_sub_confirm`,`date_confirm`) 
				VALUES ("'.$serial.'","'.$month.'","'.$dauky.'","'.$cuoiky.'",'.$kdstatus.', 1,'.$userid.',"'.$fullname.'","'.$timenow.'")';

	    	$result = mysql_query($sql) or die('500');

		}
		
	    echo '200';

	}
	

	

	userlogs($userid , $fullname , 'xác nhận số liệu');
	
	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
