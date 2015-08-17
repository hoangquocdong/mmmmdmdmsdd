<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id_sub =  isset($_REQUEST['id_sub'])? $_REQUEST['id_sub'] : 0;

$id_sub = clean_text($id_sub);
$id_sub = (int)$id_sub;

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $sql='SELECT `serial_meter`, `name_meter`, `unit_meter`, `count_point_meter`, `level_meter`, `relation_meter`, `online_status`, `status_meter`  FROM `meter` WHERE `id_sub` = '.$id_sub.' ORDER BY `relation_meter` DESC';

    $result = mysql_query($sql) or die('0');

	$level_meter_char='';
	
	$data = array();
	while($row = mysql_fetch_array($result)){
		if ($row['level_meter']==0) {$level_meter_char='C'; } else {$level_meter_char='P'; }
		$tmp = array(
				'serial_meter' => $row['serial_meter'], 
				'name_meter' => $row['name_meter'],
				'unit_meter' => $row['unit_meter'],
				'count_point_meter' => $row['count_point_meter'], 
				'relation_meter' => $row['relation_meter'], 
				'level_meter' => $row['level_meter'],
				'online_status' => $row['online_status'],
				'status_meter' => $row['status_meter'],
				'level_meter_string'=> $level_meter_char
			);
		array_push($data, $tmp);
	}

	echo json_encode($data);

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 
