<?php
session_start();

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$id =  isset($_REQUEST['id'])? $_REQUEST['id'] : 0;
$token = isset($_REQUEST['token'])? $_REQUEST['token'] : '';

$id = (int)($id);
$token = clean_text($token);

  CONNECT_DB();
    mysql_query("SET NAMES utf8");

     //$token = $password.' - '.date("d/m/y H:i:s"); 
     //$token = substr(MD5($token),0,10);
	 $sql='SELECT * FROM user WHERE ID = '.$id.' AND token = "'.$token.'"';
	 $result = mysql_query($sql) or die('0');

	 $row = 0;

	 while($rows=mysql_fetch_array($result)){
	 	$row = $rows['ID'];
	 }
	 if ($row ==  null||$row==''){
	 	die ('0');
	 }

	 die('1');
	 

	CLOSE_DB();
	unset($sql, $result, $rs, $rows);
          
?> 