<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$info =  isset($_REQUEST['info'])? $_REQUEST['info'] : '';

$start = microtime(true);

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    $returnarray = array(
		'status' => 500,
		'content'=>	'Update info fail!'
	);

	$data = json_decode($info);

    $id_pwc = (int)clean_text($data ->{'id_pwc'});
    $id_invester = (int)clean_text($data ->{'id_invester'});
    $id_sub = (int)clean_text($data ->{'id_sub'});
    $serial_meter = clean_text($data ->{'serial_meter'});
    $name_tu = clean_text($data ->{'name_tu'});
    $serial_tu = clean_text($data ->{'serial_tu'});
    $level_tu = (int)clean_text($data ->{'level_tu'});
    $relation_tu = clean_text($data ->{'relation_tu'});
    $ratio_tu = clean_text($data ->{'ratio_tu'});
    $exactlevel_tu = clean_text($data ->{'exactlevel_tu'});
    $capacity_tu = clean_text($data ->{'capacity_tu'});
    $version_tu = clean_text($data ->{'version_tu'});
    $manufacturer_tu = clean_text($data ->{'manufacturer_tu'});
    $original_tu = clean_text($data ->{'original_tu'});
    $year_production = clean_text($data ->{'year_production'});
    $timestart_tu = clean_text($data ->{'timestart_tu'});

    
    $sql = 'UPDATE `meter` SET `version_meter`="'.$version_meter.'",`level_meter`='.$level_meter.',`name_meter`="'.$name_meter.'",
    		`wiring_meter`='.$wiring_meter.',`factor_meter`='.$factor_meter.',`lineloss_meter`='.$lineloss_meter.',
    		`pconvert_meter`='.$pconvert_meter.',`levelvoltage_meter`="'.$levelvoltage_meter.'",`orderphase_meter`="'.$orderphase_meter.'",
    		`unit_meter`='.$unit_meter.',`port_transmission_meter`="'.$port_transmission_meter.'",
    		`count_point_meter`='.$count_point_meter.', `manufacturer_meter`="'.$manufacturer_meter.'",
    		`original_meter`="'.$original_meter.'",`type_meter`="'.$type_meter.'",`year_production`="'.$year_production.'",
    		`ampe_meter`="'.$ampe_meter.'",`exactlevel_meter`="'.$exactlevel_meter.'",`timestart_meter`="'.$timestart_meter.'",
    		`relation_meter`="'.$relation_meter.'",`status_meter`='.$status_meter.' 
    		WHERE `serial_meter`="'.$serial_meter.'"';
    

	$result = mysql_query($sql) or die(json_encode($returnarray));

	$returnarray = array(
		'status' => 200,
		'content'=>	'Update info succesfully!'
	);

	echo json_encode($returnarray);	

	CLOSE_DB();
	unset($sql, $result);
          
?> 