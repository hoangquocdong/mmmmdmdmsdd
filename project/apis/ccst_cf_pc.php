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
$userid =  isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$fullname_pc =  isset($_REQUEST['fullname'])? $_REQUEST['fullname'] : '';


$fullname_pc = clean_text($fullname_pc);
$token = clean_text($token);
$serial = clean_text($serial);
$month = clean_text($month);
$userid = (int)(clean_text($userid));


//if (!checktoken($userid, $token)){die ('0')}
/*
* chưa có token
*/

$pc_confirm_update = 0;

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	

	 function record_exist($serial, $month){

		$sql='SELECT * FROM `h1_confirm` WHERE `pc_confirm` = 1 AND `meter_serial` = "'.$serial.'" AND `month_confirm` = "'.$month.'"';
		
		$result = mysql_query($sql) or die('0');

		$row = 0;

		while($rows=mysql_fetch_array($result)){
			$row = $rows['ID'];
		}
		if ($row ==  null||$row==''||$row==0){
			$returnvalue = 0;
		} else {
			$returnvalue = 1;
		}

		return $returnvalue;
	}

	// error codes - http://www.w3schools.com/tags/ref_httpmessages.asp

	if (record_exist($serial, $month)) die('409'); // xung đột - pc đã xác nhận


    $sql = 'SELECT `dauky`, `cuoiky` FROM `h1_confirm` 
			WHERE `meter_serial`="'.$serial.'" AND `month_confirm` ="'.$month.'" ORDER BY `edit_count` DESC LIMIT 0,1';


    $result = mysql_query($sql) or die('0');

    $confirmdata = array();
    if (mysql_num_rows($result) > 0){
	 	while($row = mysql_fetch_array($result)){

			$confirmdata = array(
				'dauky' => $row['dauky'], 
				'cuoiky' => $row['cuoiky']
			);
		} 
		
    }

    //echo $confirmdata['dauky'];

    if (isset($confirmdata['dauky'])){
    	if ($confirmdata['dauky']!=null&&$confirmdata['dauky']!='') { $pc_confirm_update = 1;}
    }

    //echo $pc_confirm_update;

    if ($pc_confirm_update) {
    	$sql = 'UPDATE `h1_confirm` SET `pc_confirm`=1, `userid_pc_confirm`='.$userid.', 
			`fullname_pc_confirm`="'.$fullname_pc.'",`date_pc_confirm`="'.$timenow.'" 
			WHERE `meter_serial` = "'.$serial.'" AND `month_confirm`="'.$month.'"';


    	$result = mysql_query($sql) or die('0');

    	echo '200';
    } else {
    	//Nhà máy chưa xác nhận số liệu
    	echo '406';
    }

    userlogs($userid , $fullname_pc , 'xác nhận số liệu');
    
	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
