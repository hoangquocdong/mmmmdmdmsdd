<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$actioncode =  (int)isset($_REQUEST['actioncode'])? $_REQUEST['actioncode'] : 0;
$moredetail =  isset($_REQUEST['moredetail'])? $_REQUEST['moredetail'] : '';


CONNECT_DB();

mysql_query("SET NAMES utf8");

$returnarray = array(
    'status' => 500,
    'content'=> 'Create logs fail!'
);

$year_production = clean_text($data ->{'year_production'});
$timestart_ti = clean_text($data ->{'timestart_ti'});

useradminlogs($userid, $actioncode, $moredetail);


$result = mysql_query($sql) or die(json_encode($returnarray));

$returnarray = array(
    'status' => 200,
    'content'=> 'Create logs succesfully!'
);

echo json_encode($returnarray); 

CLOSE_DB();
unset($sql, $result);
          
?> 

    