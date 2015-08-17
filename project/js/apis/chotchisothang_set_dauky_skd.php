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


$fullname = clean_text($fullname);
$dauky = clean_text($dauky);
$cuoiky = clean_text($cuoiky);
$token = clean_text($token);
$serial = clean_text($serial);
$month = clean_text($month);


  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	
	if (!checktoken($userid, $token)){die ('0');}

    function record_exist($serial, $month){

    	/*
    	*	Kiêm tra dữ liệu cuối kỳ tồn tại ->>> ko cho ghi nữa ->> nên chuyển sang cơ chế xem sub_confirm 
    	*/
    	$row =  null; 

		$sql =	'SELECT `cuoiky` FROM `h1_confirm` 
				WHERE `type` = 2 AND `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'"';
		
		$result = mysql_query($sql) or die('500');

		$row = 0;

		while($rows=mysql_fetch_array($result)){
			$row = $rows['cuoiky'];
		}

		if ($row ==  null||$row==''){
			$returnvalue = 0;
		} else {
			$returnvalue = 1;
		}

		return $returnvalue;
	}


	if (record_exist($serial, $month)) die('409');


	$sql =	'SELECT `dauky` FROM `h1_confirm` 
				WHERE `type` = 2 AND `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'"';
		
	$result = mysql_query($sql) or die('500');

	$count = 0;

	while($rows=mysql_fetch_array($result)){
		$count++;
	}

	if ($count==0){

		$sql = 'INSERT INTO `h1_confirm`(`meter_serial`, `month_confirm`, `type`, `dauky`, `cuoiky`, `id_user_confirm`, 
				`fullname_sub_confirm`,`date_confirm`) 
				VALUES ("'.$serial.'","'.$month.'", 2, "'.$dauky.'","",'.$userid.',"'.$fullname.'","'.$timenow.'")';

				$result = mysql_query($sql) or die('500');

	} else if ($count==1){

		$sql = 'UPDATE `h1_confirm` SET `dauky`="'.$dauky.'", `id_user_confirm`="'.$userid.'", 
				`fullname_sub_confirm`="'.$fullname.'", `date_confirm`="'.$timenow.'"
				WHERE `type` = 2 AND `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'"';

				$result = mysql_query($sql) or die('500');

	} else if ($count>1){

		$sql = 'DELETE FROM `h1_confirm` WHERE `type` = 2 AND `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'"';

		$result = mysql_query($sql) or die('500');

		$$sql = 'INSERT INTO `h1_confirm`(`meter_serial`, `month_confirm`, `type`, `dauky`, `cuoiky`, `id_user_confirm`, 
				`fullname_sub_confirm`,`date_confirm`) 
				VALUES ("'.$serial.'","'.$month.'", 2, "'.$dauky.'","",'.$userid.',"'.$fullname.'","'.$timenow.'")';

		$result = mysql_query($sql) or die('500');

	}


    echo '200';

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
