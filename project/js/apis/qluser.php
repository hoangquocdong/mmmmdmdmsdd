<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	
	$sql='SELECT user_name, full_name, phone_number, email, office_name, phongban, register_date, writable, editable 
			FROM user WHERE ID = '.$userid.' AND enable = 1';

	$result = mysql_query($sql) or die('500');

	$rs=array();
	

	while($rsl=mysql_fetch_array($result)){

		$rs=array(
			
			"user_name" => $rsl['user_name'],
			"full_name" => $rsl['full_name'],
			"phone_number" => $rsl['phone_number'],
			"email" => $rsl['email'],
			"editable" => $rsl['editable'],
			"writable" => $rsl['writable'],
			"office_name" => $rsl['office_name'],
			"register_date" => $rsl['register_date'],
			"phongban" => $rsl['phongban']

		);

    }

    echo json_encode($rs);


	CLOSE_DB();
	unset($sql, $result, $rs, $rows);
          
?> 