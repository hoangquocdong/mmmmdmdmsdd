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

	$sql = 'SELECT `ID`, `id_sub`,`serial_meter`,`id_incident`,`id_cause`,`note_incident`,`time_incident`,
	`status_incident`,`id_user_create`,`name_user_create` FROM incident_manager';
	$result = mysql_query($sql) or die('0');
	$data = array();
	while($row = mysql_fetch_array($result)){
		$tmp = array(
			'ID' => $row['ID'],
			'id_sub' => $row['id_sub'],
			'serial_meter' => $row['serial_meter'],
			'id_incident' => $row['id_incident'],
			'id_cause' => $row['id_cause'],
			'note_incident' => $row['note_incident'],
			'time_incident' => $row['time_incident'],
			'status_incident' => $row['status_incident'],
			'id_user_create' => $row['id_user_create'],
			'name_user_create' => $row['name_user_create']
			);
		array_push($data, $tmp);
	}
	
	$sql = 'SELECT `id_incident_code`,`content_process`,`time_process`,`user_process` FROM incident_process_history';
	$result = mysql_query($sql) or die('0');
	$data1 = array();
	while($row = mysql_fetch_array($result)){
		$tmp = array(
			'id_incident_code' => $row['id_incident_code'],
			'content_process' => $row['content_process'],
			'time_process' => $row['time_process'],
			'user_process' => $row['user_process']
			);
		array_push($data1, $tmp);
	}
	
	
	for ($i=0; $i<sizeof($data); $i++){
		for ($j=0; $j<sizeof($data1); $j++){
			if ($data[$i]['ID']==$data1[$j]['id_incident_code']) {
				//echo $data[$i]['ID'].'    '.$data1[$j]['id_incident_code'];
				array_push($data[$i],$data1[$j]);		
			}
		}
	}
	
	
	//array_push($data, $data1);
	echo json_encode($data);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result);
          
?> 