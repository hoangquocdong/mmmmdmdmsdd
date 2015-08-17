<?php
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
    	* Check update menu
    */

    $mnleft = getmenuleft($token, $id);
    $validatedarr = getvalidated();
    //echo json_encode($validatedarr); exit();
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

		$sql = 'SELECT `serial_equipment`, `start_validated_h` FROM `validated` WHERE 1';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$data[$row['serial_equipment']] = $row['start_validated_h'];
		}
		return $data;
	}
	function getincident(){
		$sql = 'SELECT `id_incident`, `name_incident` FROM `incident_type`';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'id_incident' => $row['id_incident'],
				'name_incident' => $row['name_incident'] 					
				);
			array_push($data, $tmp);
		}
		return $data;
	}
	function getuser($id){
	$sql='SELECT user_name, full_name, phone_number, email, office_name, phongban, register_date, writable, editable 
			FROM user WHERE ID = '.$id.' AND enable = 1';

	$result = mysql_query($sql) or die('0');
	$data=array();

	while($rsl=mysql_fetch_array($result)){
		$tmp=array(
			"user_name" => $rsl['user_name'],
			"full_name" => $rsl['full_name'],
			"phone_number" => $rsl['phone_number'],
			"email" => $rsl['email'],
			"editable" => $rsl['editable'],
			"writable" => $rsl['writable'],
			"office_name" => $rsl['office_name'],
			"register_date" => $rsl['register_date'],
			"phongban" => $rsl['phongban']
		);
		array_push($data, $tmp);
    }
		return $data;
	}	
	function getincident_cause(){
		$sql = 'SELECT `id_incident`, `id_cause`, `name_cause` FROM `incident_cause`';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'id_incident' => $row['id_incident'],
				'id_cause' => $row['id_cause'], 
				'name_cause' => $row['name_cause'] 				
				);
			array_push($data, $tmp);
		}
		return $data;
	}
	function getmeterinfo($idsub, $validated){
		$idsub = (int)$idsub;
		$sql='SELECT `ID`, `id_sub`, `id_pwc`, `id_invester`, `serial_meter`, `version_meter`, `level_meter`, `name_meter`, `levelvoltage_meter`,
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
			

			$tmp = array(
					'stt' => $stt.'',
					'id' => $row['ID'],
					'id_sub' => $row['id_sub'], 
					'id_pwc' => $row['id_pwc'], 
					'id_invester' => $row['id_invester'], 
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
					'validated' => $validatevalue
				);
			$stt++;
			array_push($data, $tmp);
		}
		return $data;
	}

	function getlistequip($mnleft, $token, $id, $validatedarr){

		$object = $mnleft;

		$idsub = 'id_sub';$meter = 'meterinfo';$tu = 'tuinfo';$ti = 'tiinfo';

		for ($i=0; $i<sizeof($object); $i++){
			for ($j=0; $j<sizeof($object[$i][1]); $j++){
				$sid = $object[$i][1][$j] ->$idsub;
				$meterinfo = getmeterinfo($sid, $validatedarr);
								
				$object[$i][1][$j] ->$meter = $meterinfo;
			}
		}

		return $object;
	}
	
	
	$equiplist = getlistequip($mnleft, $token, $id, $validatedarr);

	//$tmp1=getuser($id);array_push($equiplist, $tmp1); 
	$tmp2 = getincident_cause();array_push($equiplist, $tmp2); 
	$tmp3 = getincident(); array_push($equiplist, $tmp3);

	
	echo json_encode($equiplist);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 