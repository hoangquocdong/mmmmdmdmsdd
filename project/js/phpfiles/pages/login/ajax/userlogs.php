<?php
session_start();								//không đặt session start ở đây thì gán biến session vẫn ko báo lỗi nhưng ko lưu lại giá trị được.

require('../../../../donghq.php');
require(HOMEPATH.'libs/db_functions.php');
require(HOMEPATH.'libs/common_functions.php');
CONNECT_DB();

$myusername=cleanstr($_REQUEST['user']);
$activity=isset($_REQUEST["status"])? $_REQUEST["status"] : 2; 
if ($activity=='') $activity = 2;
//$activity=0;
//$time_now=strtotime(date("m/d/Y H:i:s"));
$time_now=time();
$sql = "INSERT INTO userlogs (username, activities, time) VALUES ('$myusername',$activity,$time_now)";
$result=mysql_query($sql);
if ($result) echo  'Ban da thoat khoi ung dung ';
else echo 'ERROR';
//sleep(55);
?>