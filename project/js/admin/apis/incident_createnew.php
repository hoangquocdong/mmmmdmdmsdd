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
        'content'=> 'Tạo phiếu xử lý sự cố chưa được!'
    );

    $data = json_decode($info);

    $id_sub = (int)clean_text($data ->{'id_sub'});
    $serial_meter = clean_text($data ->{'serial_meter'});
    $id_incident = (int)clean_text($data ->{'id_incident'});
    $id_cause = (int)clean_text($data ->{'id_cause'});
    $note_incident = clean_text($data ->{'note_incident'});
    $time_incident = (int)clean_text($data ->{'time_incident'});
    $status_incident = (int)clean_text($data ->{'status_incident'});
    $id_user_create = (int)clean_text($data ->{'id_user_create'});
    $name_user_create = clean_text($data ->{'name_user_create'});

    $content_process = clean_text($data ->{'content_process'});	
	//die (json_encode($data));

	
    $sql = 'INSERT INTO `incident_manager`(`id_sub`, `serial_meter`, `id_incident`, `id_cause`, 
            `note_incident`, `time_incident`, `status_incident`, `id_user_create`, `name_user_create`) 
            VALUES ('.$id_sub.',"'.$serial_meter.'",'.$id_incident.','.$id_cause.',
                "'.$note_incident.'",'.$time_incident.','.$status_incident.','.$id_user_create.',"'.$name_user_create.'")';

    $result = mysql_query($sql) or die(json_encode($returnarray));
	
	// Lấy ID của phiếu mới ghi vào bảng quản lý sự cố để điền id của phiếu này vào bảng lịch sử xử lý
	
	$sql = 'SELECT `ID` FROM `incident_manager` WHERE 1 ORDER BY `ID` DESC LIMIT 0,1';
    $result = mysql_query($sql) or die(json_encode($returnarray));
	
	
    while($row = mysql_fetch_array($result)){
        $id_incident_code = $row['ID'];
    }
	
	// Cập nhật lịch sử thao tác đầu tiên vào bảng lịch sử xử lý
	
    $sql = 'INSERT INTO `incident_process_history`(`id_incident_code`, `content_process`, `time_process`, `user_process`) 
            VALUES ('.$id_incident_code.',"'.$content_process.'",'.$time_incident.',"'.$name_user_create.'")';

    $result = mysql_query($sql) or die(json_encode($returnarray));	
	

    $returnarray = array(
        'status' => 200,
        'content'=> 'Tạo phiếu xử lý sự cố thành công!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    