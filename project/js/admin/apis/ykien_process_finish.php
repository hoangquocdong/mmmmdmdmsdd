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

    $idykien_record = (int)clean_text($data ->{'idykien_record'});
    $content_process = clean_text($data ->{'content_process'});
    $time_process = (int)clean_text($data ->{'time_process'}); 
    $user_process = clean_text($data ->{'user_process'});
	$read_status = 1;
   
    $sql = 'INSERT INTO `ykien_process`(`idykien_record`, `content_process`, `time_process`, `user_process`) 
            VALUES ('.$idykien_record.',"'.$content_process.'",'.$time_process.',"'.$user_process.'")';

    $result = mysql_query($sql) or die(json_encode($returnarray));
	
    $sql = 'UPDATE `ykienphanhoi` SET `read_status`='.$read_status.'
            WHERE `ID`='.$idykien_record;
	
	$result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Cập nhật xử lý sự cố thành công!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    