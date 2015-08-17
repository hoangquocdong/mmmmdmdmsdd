<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$info =  isset($_REQUEST['info'])? $_REQUEST['info'] : '';

$start = microtime(true);

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    $returnarray = array(
        'status' => 500,
        'content'=> 'Create TI fail!'
    );

    $data = json_decode($info);

    $id_pwc = (int)clean_text($data ->{'id_pwc'});
    $id_invester = (int)clean_text($data ->{'id_invester'});
    $id_sub = (int)clean_text($data ->{'id_sub'});
    $serial_meter = clean_text($data ->{'serial_meter'});
    $name_ti = clean_text($data ->{'name_ti'});
    $serial_ti = clean_text($data ->{'serial_ti'});
    $level_ti = (int)clean_text($data ->{'level_ti'});
    $relation_ti = clean_text($data ->{'relation_ti'});
    $ratio_ti = clean_text($data ->{'ratio_ti'});
    $exactlevel_ti = clean_text($data ->{'exactlevel_ti'});
    $capacity_ti = clean_text($data ->{'capacity_ti'});
    $version_ti = clean_text($data ->{'version_ti'});
    $manufacturer_ti = clean_text($data ->{'manufacturer_ti'});
    $original_ti = clean_text($data ->{'original_ti'});
    $year_production = clean_text($data ->{'year_production'});
    $timestart_ti = clean_text($data ->{'timestart_ti'});
    
    $selectti = 0;

    $sql = 'SELECT `ID` FROM `ti` WHERE `serial_ti` = "'.$serial_ti.'"';
    $result = mysql_query($sql) or die(json_encode($returnarray));

    while($row = mysql_fetch_array($result)){
        $selectti = (int)$row['ID'];
    }

    if ($selectti != 0) {
        $returnarray = array(
            'status' => 500,
            'content'=> 'TI serial is existed!'
        );
        die(json_encode($returnarray));
    }

    $sql = 'INSERT INTO `ti`(`id_pwc`, `id_invester`, `id_sub`, `serial_meter`, 
            `name_ti`, `serial_ti`, `level_ti`, `relation_ti`, `ratio_ti`, 
            `exactlevel_ti`, `capacity_ti`, `version_ti`, `manufacturer_ti`, 
            `original_ti`, `year_production`, `timestart_ti`) 
            VALUES ('.$id_pwc.','.$id_invester.','.$id_sub.',"'.$serial_meter.'",
                "'.$name_ti.'","'.$serial_ti.'",'.$level_ti.',"'.$relation_ti.'","'.$ratio_ti.'",
                "'.$exactlevel_ti.'","'.$capacity_ti.'","'.$version_ti.'","'.$manufacturer_ti.'",
                "'.$original_ti.'","'.$year_production.'","'.$timestart_ti.'")';

    
    $result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Create TI info succesfully!'
    );

    $actioncode = 13; //edit meter profile
    $moredetail = 'new TI : '.$serial_ti;
    useradminlogs($userid, $actioncode, $moredetail);

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    