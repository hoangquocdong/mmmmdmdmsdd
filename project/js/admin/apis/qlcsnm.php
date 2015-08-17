<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$month =  isset($_REQUEST['month'])? $_REQUEST['month'] : '';
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$id =  (int)isset($_REQUEST['id'])? $_REQUEST['id'] : 0;

$month = clean_text($month);
$token = clean_text($token);

$start = microtime(true);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
    
    /*
    * get all report records
    */
	
	$sql = 'SELECT `meter_serial`, `dauky`, `cuoiky`, `pc_confirm`, `fullname_sub_confirm`, `fullname_pc_confirm`, `date_pc_confirm`, `date_confirm`, `edit_date_confirm`, `edit_count` 
			FROM `h1_confirm` 
			WHERE `month_confirm` ="'.$month.'"';

    $result = mysql_query($sql) or die('0');

    $confirmdata = array();
    if (mysql_num_rows($result) > 0){
	 	while($row = mysql_fetch_array($result)){

			$tmp = array(
				'meter_serial' => $row['meter_serial'],
				'dauky' => $row['dauky'], 
				'cuoiky' => $row['cuoiky'], 
				'pc_confirm' => $row['pc_confirm'],
				'fullname_sub_confirm' => $row['fullname_sub_confirm'], 
				'fullname_pc_confirm' => $row['fullname_pc_confirm'], 
				'date_confirm' => $row['date_confirm'],
				'date_pc_confirm' => $row['date_pc_confirm'],
				'edit_date_confirm' => $row['edit_date_confirm'], 
				'edit_count' => $row['edit_count']
			);
			 $confirmdata[$row['meter_serial']]=$tmp;
		} 
		
    }
    //echo json_encode($confirmdata);	exit();
 	//echo '<pre>';print_r($confirmdata);   exit();

    /*
    	* Check update menu
    */
    $sql = 'SELECT `flag_change` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
	$result = mysql_query($sql) or die('0');
	$rs = 0;
	while($row = mysql_fetch_array($result)){
		$rs = $row['flag_change'];
	}
	if ($rs == 1) {
		include('menuleftupdate.php');
	}

	/*
    	* End of Check update menu
    */

    $mnleft = getmenuleft($token, $id);
    $validatedarr = getvalidated();

	function getmenuleft($token, $id){

		$return='';

		$sql = 'SELECT `cacheall` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id;
		$result = mysql_query($sql) or die('0');
		
		if (mysql_num_rows($result)){
			$rows = mysql_num_rows($result);
			if ($rows == 1) { 
				while($rsl=mysql_fetch_array($result)){
					$val = $rsl['cacheall'];
			    }
			    $return = $val;
			} else {
				$return = '';
			}
		} else {
			$return = '';
		}

		return json_decode($return);
	}

	function getvalidated(){

		$sql = 'SELECT `serial_equipment`, `time_validated` FROM `validated` WHERE 1';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$data[$row['serial_equipment']] = $row['time_validated'];
		}
		return $data;
	}

	//echo json_encode($confirmdata);

	function getmeterinfo($idsub, $validated, $confirmdata){
		$idsub = (int)$idsub;
		$sql='SELECT `ID`, `serial_meter`, `version_meter`, `level_meter`, `name_meter`, `levelvoltage_meter`,
					 `manufacturer_meter`, `original_meter`, `type_meter`, `year_production`, `ampe_meter`, 
					 `exactlevel_meter`, `timestart_meter`, `location_meter`, `relation_meter`, `lasttime_programming`, `count_programming` 
					 FROM `meter` WHERE `id_sub` = '.$idsub.' ORDER BY `relation_meter` ASC'; // THÊM ORDER BY RELATIONSHIP => DE THEO CAP
		$result = mysql_query($sql) or die('0');
		$data = array();
		$stt = 1;
		while($row = mysql_fetch_array($result)){
			$validatevalue = 'not available';
			if( isset($validated[$row['serial_meter']])){
			    $validatevalue = $validated[$row['serial_meter']];
			}

			/*
			*	Get confirm info 
			*/

			$dlxacnhan = $dl_ng_xacnhan = $dl_ngay_xn = $subxacnhan = $sub_ng_xacnhan = $sub_ngay_xn = '';
			if (isset($confirmdata[$row['serial_meter']])){
				$dlxacnhan = $confirmdata[$row['serial_meter']]['pc_confirm'];
				$dl_ng_xacnhan = $confirmdata[$row['serial_meter']]['fullname_pc_confirm'];
				$dl_ngay_xn = $confirmdata[$row['serial_meter']]['date_pc_confirm'];
				$subxacnhan = '1';
				$sub_ng_xacnhan = $confirmdata[$row['serial_meter']]['fullname_sub_confirm'];
				$sub_ngay_xn = $confirmdata[$row['serial_meter']]['date_confirm'];
			}

			$tmp = array(
					'stt' => $stt.'',
					'id' => $row['ID'], 
					'serial_meter' => $row['serial_meter'],
					'version_meter' => $row['version_meter'],
					'level_meter' => $row['level_meter'],
					'name_meter' => $row['name_meter'],
					'levelvoltage_meter' => $row['levelvoltage_meter'],
					'manufacturer_meter' => $row['manufacturer_meter'], 
					'original_meter' => $row['original_meter'],
					'type_meter' => $row['type_meter'],
					'year_production' => $row['year_production'],
					'ampe_meter' => $row['ampe_meter'],
					'exactlevel_meter' => $row['exactlevel_meter'],
					'timestart_meter' => $row['timestart_meter'], 
					'location_meter' => $row['location_meter'],
					'relation_meter' => $row['relation_meter'],
					'lasttime_programming' => $row['lasttime_programming'],
					'count_programming' => $row['count_programming'],
					'validated' => $validatevalue,
					'dlxacnhan' => $dlxacnhan, 
					'dl_nguoi_xn' => $dl_ng_xacnhan, 
					'dl_ngay_xn' => $dl_ngay_xn, 
					'subxacnhan' => $subxacnhan, 
					'sub_nguoi_xn' => $sub_ng_xacnhan, 
					'sub_ngay_xn' => $sub_ngay_xn
				);
			$stt++;
			//echo json_encode($tmp);
			array_push($data, $tmp);
		}

		return $data;
	}

	function getlistequip($mnleft, $token, $id, $validatedarr, $confirmdata){

		$object = $mnleft;
		$idsub = 'id_sub';$meter = 'meterinfo';

		for ($i=0; $i<sizeof($object); $i++){
			for ($j=0; $j<sizeof($object[$i][1]); $j++){
				$sid = $object[$i][1][$j] ->$idsub;
				$meterinfo = getmeterinfo($sid, $validatedarr, $confirmdata);
				$object[$i][1][$j] ->$meter = $meterinfo;
			}
		}

		return $object;
	}

	//echo $mnleft, $token, $id, json_encode($validatedarr);
	$equiplist = getlistequip($mnleft, $token, $id, $validatedarr, $confirmdata);
	echo json_encode($equiplist);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 