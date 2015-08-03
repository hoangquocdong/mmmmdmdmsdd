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
        'content'=> 'Create tu fail!'
    );

    $data = json_decode($info);

    $substation = clean_text($data ->{'substation'});
    $meter = (int)clean_text($data ->{'meter'});
	
	die (json_encode($data));
	
    $sql = 'INSERT INTO `tu`(`id_pwc`, `id_invester`, `id_sub`, `serial_meter`, 
            `name_tu`, `serial_tu`, `level_tu`, `relation_tu`, `ratio_tu`, 
            `exactlevel_tu`, `capacity_tu`, `version_tu`, `manufacturer_tu`, 
            `original_tu`, `year_production`, `timestart_tu`) 
            VALUES ('.$id_pwc.','.$id_invester.','.$id_sub.',"'.$serial_meter.'",
                "'.$name_tu.'","'.$serial_tu.'",'.$level_tu.',"'.$relation_tu.'","'.$ratio_tu.'",
                "'.$exactlevel_tu.'","'.$capacity_tu.'","'.$version_tu.'","'.$manufacturer_tu.'",
                "'.$original_tu.'","'.$year_production.'","'.$timestart_tu.'")';

    
    $result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Create tu info succesfully!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    