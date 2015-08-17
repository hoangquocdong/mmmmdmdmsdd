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

	function get_ykien_process($process_code){
	
		$sql = 'SELECT `idykien_record`,`content_process`,`time_process`,`user_process` FROM ykien_process WHERE `idykien_record` = '.$process_code.'';
		$result = mysql_query($sql) or die('0');
		$data = array();
		while($row = mysql_fetch_array($result)){
			$tmp = array(
				'idykien_record' => $row['idykien_record'],
				'content_process' => $row['content_process'],
				'time_process' => date('d-m-Y H:i:s', $row['time_process']),
				'user_process' => $row['user_process']
				);
			array_push($data, $tmp);
		}
		return $data;
	}	

    /*
    * processing ykienphanhoi
    */
    $sql = 'SELECT `ID`, `userid`, `fullname`, `email`, `phone`, `content`, `pcid`, `read_status` , `time` 
            FROM ykienphanhoi ' ;
	
    $result = mysql_query($sql) or die(json_encode($returnarray));

    $data = array();
    while($rs=mysql_fetch_array($result)){
	
	$listprocess=get_ykien_process ($rs['ID']);
	$pos=strpos($rs['content'], ' ', 100);
	$short_content =substr($rs['content'],0,$pos );
	
        $tmp = array(
                'ID' => $rs['ID'],
                'userid' => $rs['userid'],
                'time' => date('d-m-Y H:i:s', $rs['time']),
                'fullname' => $rs['fullname'],
                'email' => $rs['email'],
                'phone' => $rs['phone'],
                'content' => $rs['content'],
                'short_content' => $short_content,				
                'pcid' => $rs['pcid'],
                'read_status' => $rs['read_status'],
                'history_process' => $listprocess				
            );

        array_push($data, $tmp);
    }

	echo json_encode($data);

	//echo $time_elapsed_us = microtime(true) - $start;

	CLOSE_DB();
	unset($sql, $result);
          
?> 