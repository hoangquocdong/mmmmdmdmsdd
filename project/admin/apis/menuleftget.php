<?php

/*

	*	Usage:
	*	Get all pc sub ... include status = 0 for statistics

*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id =  isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token =  isset($_REQUEST['token'])? $_REQUEST['token'] : '';
$value =  isset($_REQUEST['value'])? $_REQUEST['value'] : '';

  CONNECT_DB();
    mysql_query("SET NAMES utf8");


    $sql = 'SELECT `cache` FROM `user` WHERE `token` = "'.$token.'" AND `ID` = '.$id.' AND `flag_change` = 0';
	$result = mysql_query($sql) or die('0');
	
	if (mysql_num_rows($result)){
		$rows = mysql_num_rows($result);
		if ($rows == 1) { 
			while($rsl=mysql_fetch_array($result)){
				$val = $rsl['cache'];
		    }
		    echo $val;
		} else {
			include('menuleftfnc.php');
			echo $menu = json_encode(amrupdatecache($id, $token));
			include('menuleftfncall.php');
			$menu = updatecache($id, $token);
		}
	} else {
		include('menuleftfnc.php');
		echo $menu = json_encode(amrupdatecache($id, $token));
		include('menuleftfncall.php');
		$menu = updatecache($id, $token);
	}


	CLOSE_DB();
	unset($sql, $result, $rs);
          
?> 