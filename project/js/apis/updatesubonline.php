<?php
/*

	* get number of online/offline substations

*/
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id =  isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$value =  isset($_REQUEST['value'])? $_REQUEST['value'] : '';

$start = microtime(true);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $listsubstatus = array();

    $sql = 'SELECT `permission` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
		
		$result = mysql_query($sql) or die('0');
		
		$permission ='';	//init value to json_encode function

		while($row=mysql_fetch_array($result)){
			$permission = $row['permission'];
	    }

		$permission = json_decode($permission);

		if( sizeof($permission) == 0) die('0');
	    		
	    
	    $subid_arr = array();
	    foreach($permission as $key => $value) {
	    	if (isset($value[1])){
	    		foreach($value[1] as $key => $val) {
	    			array_push($subid_arr, $val);
	    		}
	    	}
	    }

	    $ids = join(',',$subid_arr);  
		
	    $sql = "SELECT `connection_sub`, `id_sub` FROM `substation_power` WHERE `id_sub` IN ($ids)";
		$result = mysql_query($sql) or die('0');
		$online = 0; $offline = 0;
		while($row = mysql_fetch_array($result)){
			if ($row['connection_sub'] == 1) {$online++;} else if ($row['connection_sub'] == 0) {$offline++;}
			$keysub = 'sub_'.$row['id_sub'];
			$listsubstatus[$keysub] = $row['connection_sub'];
		}
		//$rs = array($online , $offline);
		$rs = array();
		$rs['online'] = $online;
		$rs['offline'] = $offline;
		$rs['listsubstatus'] = $listsubstatus;

		echo json_encode($rs);
	    

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $permission, $subid_arr, $ids, $row);
          
?> 