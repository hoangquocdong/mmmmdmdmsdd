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

	function get_incident_process_history($process_code){
	
		$sql = 'SELECT `id_incident_code`,`content_process`,`time_process`,`user_process` FROM incident_process_history WHERE `id_incident_code` = '.$process_code.'';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'id_incident_code' => $row['id_incident_code'],
				'content_process' => $row['content_process'],
				'time_process' => date('d-m-Y H:i:s', $row['time_process']),
				'user_process' => $row['user_process']
				);
			array_push($data, $tmp);
		}
		return $data;
	}	
	function get_name_sub($id_sub){
	
		$sql = 'SELECT `name_sub` FROM substation_power WHERE `id_sub` = '.$id_sub.'';
		$result = mysql_query($sql) or die('0');
		while($row = mysql_fetch_array($result)){
			$namesub = $row['name_sub'];
		}	
		return $namesub;
	}	
	
	function get_name_incident($id_incident){
	
		$sql = 'SELECT `name_incident` FROM incident_type WHERE `id_incident` = '.$id_incident.'';
		$result = mysql_query($sql) or die('0');
		while($row = mysql_fetch_array($result)){
			$name_incident = $row['name_incident'];
		}	
		return $name_incident;
	}	
	function get_name_cause($id_cause){
	
		$sql = 'SELECT `name_cause` FROM incident_cause WHERE `id_cause` = '.$id_cause.'';
		$result = mysql_query($sql) or die('0');
		while($row = mysql_fetch_array($result)){
			$name_cause = $row['name_cause'];
		}	
		return $name_cause;
	}		
	
	$sql = 'SELECT `ID`, `id_sub`,`serial_meter`,`id_incident`,`id_cause`,`note_incident`,`time_incident`,
	`status_incident`,`id_user_create`,`name_user_create` FROM incident_manager';
	$result = mysql_query($sql) or die('0');
	$data = array();
	while($row = mysql_fetch_array($result)){
		
		$listprocess=get_incident_process_history ($row['ID']);
		$namesub=get_name_sub ($row['id_sub']);
		$name_incident= get_name_incident ($row['id_incident']);
		$name_cause= get_name_cause ($row['id_cause']);
		
		$tmp = array(
			'ID' => $row['ID'],
			'id_sub' => $row['id_sub'],
			'namesub' => $namesub,			
			'serial_meter' => $row['serial_meter'],
			'id_incident' => $row['id_incident'],
			'name_incident' => $name_incident,			
			'id_cause' => $row['id_cause'],
			'name_cause' => $name_cause,			
			'note_incident' => $row['note_incident'],
			'time_incident' => date('d-m-Y H:i:s', $row['time_incident']),
			'status_incident' => $row['status_incident'],
			'id_user_create' => $row['id_user_create'],
			'name_user_create' => $row['name_user_create'],
			'process_history' => $listprocess
		);
		array_push($data, $tmp);
	}
	

	//$data2 = array();
	//for ($i=0; $i<sizeof($data); $i++){
	//	for ($j=0; $j<sizeof($data1); $j++){
	//		if ($data[$i]['ID']==$data1[$j]['id_incident_code']) {
				//echo $data[$i]['ID'].'    '.$data1[$j]['id_incident_code'];
	//			array_push($data2,$data1[$j]);		
	//		}
	//	}
	//	array_push($data[$i],$data2);
	//}
	
	
	//array_push($data, $data1);
	echo json_encode($data);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result);
          
?> 