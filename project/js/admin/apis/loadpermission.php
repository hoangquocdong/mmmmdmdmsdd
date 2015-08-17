<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;

/*
* thiếu user id - phân quyền
*/


  	CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $sql = 'SELECT `permission` FROM `user` WHERE ID = '.$userid;

    $result = mysql_query($sql) or die('0');

    $value = '';

	while($row = mysql_fetch_array($result)){
		$value = $row['permission'];
	}

	echo $value;
	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 