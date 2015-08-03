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
        'content'=> 'Create meter fail!'
    );

    $data = json_decode($info);

    $id_sub = (int)clean_text($data ->{'id_sub'});
    $serial_meter = clean_text($data ->{'serial_meter'});
    $version_meter = clean_text($data ->{'version_meter'});
    $level_meter = (int)clean_text($data ->{'level_meter'});
    $name_meter = clean_text($data ->{'name_meter'});
    $wiring_meter = (int)clean_text($data ->{'wiring_meter'});
    $factor_meter = (double)clean_text($data ->{'factor_meter'});
    $lineloss_meter = (float)clean_text($data ->{'lineloss_meter'});
    $pconvert_meter = (float)clean_text($data ->{'pconvert_meter'});
    $levelvoltage_meter = clean_text($data ->{'levelvoltage_meter'});
    $orderphase_meter = clean_text($data ->{'orderphase_meter'});
    $unit_meter = (int)clean_text($data ->{'unit_meter'});
    $port_transmission_meter = clean_text($data ->{'port_transmission_meter'});
    $count_point_meter = (int)clean_text($data ->{'count_point_meter'});
    $manufacturer_meter = clean_text($data ->{'manufacturer_meter'});
    $original_meter = clean_text($data ->{'original_meter'});
    $type_meter = clean_text($data ->{'type_meter'});
    $year_production = clean_text($data ->{'year_production'});
    $ampe_meter = clean_text($data ->{'ampe_meter'});
    $exactlevel_meter = clean_text($data ->{'exactlevel_meter'});
    $timestart_meter = clean_text($data ->{'timestart_meter'});
    $relation_meter = clean_text($data ->{'relation_meter'}); 
    $id_pwc = (int)clean_text($data ->{'id_pwc'});
    $id_invester = (int)clean_text($data ->{'id_invester'});
    $status_meter = (int)clean_text($data ->{'status_meter'});


    $sql = 'INSERT INTO `meter`(`id_sub`, `serial_meter`, `version_meter`, `level_meter`, `name_meter`, 
            `wiring_meter`, `factor_meter`, `lineloss_meter`, `pconvert_meter`, `levelvoltage_meter`, 
            `orderphase_meter`, `unit_meter`, `port_transmission_meter`, `count_point_meter`, 
            `manufacturer_meter`, `original_meter`, `type_meter`, `year_production`, 
            `ampe_meter`, `exactlevel_meter`, `timestart_meter`, `relation_meter`, `id_pwc`, 
            `id_invester`, `status_meter`) 
            VALUES ('.$id_sub.',"'.$serial_meter.'","'.$version_meter.'",'.$level_meter.',"'.$name_meter.'",
                '.$wiring_meter.','.$factor_meter.','.$lineloss_meter.','.$pconvert_meter.',"'.$levelvoltage_meter.'",
                "'.$orderphase_meter.'",'.$unit_meter.',"'.$port_transmission_meter.'",'.$count_point_meter.',
                "'.$manufacturer_meter.'","'.$original_meter.'","'.$type_meter.'","'.$year_production.'",
                "'.$ampe_meter.'","'.$exactlevel_meter.'","'.$timestart_meter.'","'.$relation_meter.'",'.$id_pwc.',
                '.$id_invester.','.$status_meter.')';

    $result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Create meter info succesfully!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    