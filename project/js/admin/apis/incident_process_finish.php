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
        'content'=> 'Cập nhật xử lý sự cố chưa được!'
    );

    $data = json_decode($info);

    $id_incident_code = (int)clean_text($data ->{'id_incident_code'});
    $content_process = clean_text($data ->{'content_process'});
    $time_process = (int)clean_text($data ->{'time_process'}); 
    $user_process = clean_text($data ->{'user_process'});
	$status_incident = 1;
   
    $sql = 'INSERT INTO `incident_process_history`(`id_incident_code`, `content_process`, `time_process`, `user_process`) 
            VALUES ('.$id_incident_code.',"'.$content_process.'",'.$time_process.',"'.$user_process.'")';

    $result = mysql_query($sql) or die(json_encode($returnarray));
	
    $sql = 'UPDATE `incident_manager` SET `status_incident`='.$status_incident.'
            WHERE `ID`='.$id_incident_code;
	
	$result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Cập nhật xử lý sự cố thành công!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    