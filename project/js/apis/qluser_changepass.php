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
$password = isset($_REQUEST['password'])? $_REQUEST['password'] : '';
$newpassword = isset($_REQUEST['newpassword'])? $_REQUEST['newpassword'] : '';

$password = clean_text($password);
$newpassword = clean_text($newpassword);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");
	
	$sql='SELECT user_name
			FROM user WHERE password = "'.MD5($password).'" AND ID = '.$userid.' AND enable = 1';

	$result = mysql_query($sql) or die('500');

	$rs=array();
	
	$count = 0; $value='';
	while($rs=mysql_fetch_array($result)){

		$value = $rs['user_name'];
		$count++;

    }
    //echo $count;
    if ($count==1){
    	$sql='UPDATE `user` SET `password`="'.MD5($newpassword).'"
    			WHERE ID = '.$userid;
    	$result = mysql_query($sql) or die('500');
    	echo '200';
    } else {
    	echo '404';
    }
    

	CLOSE_DB();
	unset($sql, $result, $rs, $rows);
          
?> 