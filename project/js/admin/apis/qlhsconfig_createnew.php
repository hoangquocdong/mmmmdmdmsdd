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
        'content'=> 'Create documents profile fail!'
    );

    $data = json_decode($info);

    $profileid = (int)clean_text($data ->{'profileid'});
    $profiletype = (int)clean_text($data ->{'profiletype'});
    $profilesubid = (int)clean_text($data ->{'profilesubid'});
    $profile_name = clean_text($data ->{'profile_name'});
    $profilenumber = clean_text($data ->{'profilenumber'});
    $profilelink = clean_text($data ->{'profilelink'});
    //$profiletimestart = clean_text($data ->{'profiletimestart'});
    $profiletimestart = $timenow=date("d/m/y H:i:s");

    $sql = 'INSERT INTO `profile_sub`(`datatype`, `id_sub`, `name_file`, `link_file`, 
            `time_start_file`, `number_file`) 
            VALUES ('.$profiletype.','.$profilesubid.',"'.$profile_name.'","'.$profilelink.'",
            "'.$profiletimestart.'","'.$profilenumber.'")';


    $result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Create documents profile info succesfully!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    