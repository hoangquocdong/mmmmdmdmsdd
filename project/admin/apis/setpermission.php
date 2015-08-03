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

/*
* thiếu user id - phân quyền
*/

$id = (int)$id;
$token = clean_text($token);
$value = clean_text($value);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

    $sql = 'UPDATE `user` SET `flag_change`=1, `permission`="'.$value.'" WHERE ID = '.$id;

    
    $result = mysql_query($sql) or die('0');

	$rows = 0;
	
	if ($result){
		echo '1';
	} else { echo "0";}
		

	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 