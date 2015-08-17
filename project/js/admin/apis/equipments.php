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

	function getmeterinfo($idsub, $validated){
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
			
			$listtu = gettuinfo($stt, $row['serial_meter'],$idsub, $validated);
			$listti = gettiinfo($stt, $row['serial_meter'],$idsub, $validated);

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
					'listtu' => $listtu,
					'listti' => $listti
				);
			$stt++;
			array_push($data, $tmp);
		}
		return $data;
	}

	function gettuinfo($stt, $serial_meter, $idsub, $validated){
		$idsub = (int)$idsub;
		$sttu = 1;
		$sql='SELECT `ID`, `serial_tu`, `name_tu`, `level_tu`, `relation_tu`, `ratio_tu`, `exactlevel_tu`, 
					 `capacity_tu`, `version_tu`, `manufacturer_tu`, `original_tu`, `year_production`, `timestart_tu`
					  FROM `tu` WHERE `serial_meter` = "'.$serial_meter.'" AND `id_sub` = '.$idsub.' ORDER BY `relation_tu` ASC'; // THÊM ORDER BY RELATIONSHIP => DE THEO CAP
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$validatevalue = 'not available';
			if( isset($validated[$row['serial_tu']])){
			    $validatevalue = $validated[$row['serial_tu']];
			}
			$tmp = array(
					'stt' => $stt.'.'.$sttu, 
					'id' => $row['ID'], 
					'serial_tu' => $row['serial_tu'],
					'name_tu' => $row['name_tu'],
					'level_tu' => $row['level_tu'],
					'relation_tu' => $row['relation_tu'],
					'ratio_tu' => $row['ratio_tu'], 
					'exactlevel_tu' => $row['exactlevel_tu'],
					'capacity_tu' => $row['capacity_tu'],
					'version_tu' => $row['version_tu'],
					'manufacturer_tu' => $row['manufacturer_tu'],
					'original_tu' => $row['original_tu'],
					'year_production' => $row['year_production'], 
					'timestart_tu' => $row['timestart_tu'],
					'validated' => $validatevalue
				);
			$sttu++;
			array_push($data, $tmp);
		}
		return $data;
	}

	function gettiinfo($stt, $serial_meter, $idsub, $validated){
		$idsub = (int)$idsub;
		$stti = 1;
		$sql='SELECT `ID`, `serial_ti`, `name_ti`, `level_ti`, `relation_ti`, `ratio_ti`, `exactlevel_ti`, 
					 `capacity_ti`, `version_ti`, `manufacturer_ti`, `original_ti`, `year_production`, `timestart_ti`
					  FROM `ti` WHERE `serial_meter` = "'.$serial_meter.'" AND `id_sub` = '.$idsub.' ORDER BY `relation_ti` ASC';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$validatevalue = 'not available';
			if( isset($validated[$row['serial_ti']])){
			    $validatevalue = $validated[$row['serial_ti']];
			}
			$tmp = array(
					'stt' => $stt.'.'.$stti,
					'id' => $row['ID'], 
					'serial_ti' => $row['serial_ti'],
					'name_ti' => $row['name_ti'],
					'level_ti' => $row['level_ti'],
					'relation_ti' => $row['relation_ti'],
					'ratio_ti' => $row['ratio_ti'], 
					'exactlevel_ti' => $row['exactlevel_ti'],
					'capacity_ti' => $row['capacity_ti'],
					'version_ti' => $row['version_ti'],
					'manufacturer_ti' => $row['manufacturer_ti'],
					'original_ti' => $row['original_ti'],
					'year_production' => $row['year_production'], 
					'timestart_ti' => $row['timestart_ti'],
					'validated' => $validatevalue
				);
			$stti++;
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
	echo json_encode($equiplist);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 