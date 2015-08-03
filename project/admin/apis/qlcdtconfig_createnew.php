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
        'content'=> 'Create investor fail!'
    );

    $data = json_decode($info);

    $name = clean_text($data ->{'name'});
    $invid = (int)clean_text($data ->{'id'});
    $phone = clean_text($data ->{'phone'});
    $email = clean_text($data ->{'email'});
    $address = clean_text($data ->{'address'});


    $sql = 'SELECT `id_investor` FROM `investor` WHERE 1 ORDER BY `ID` DESC LIMIT 0,1';
    $result = mysql_query($sql) or die(json_encode($returnarray));

    while($row = mysql_fetch_array($result)){
        $invid = $row['id_investor']+1;
    }

    $sql = 'INSERT INTO `investor`(`id_investor`, `name_investor`, 
            `phone_investor`, `email_investor`, `adress_investor`)
            VALUES ('.$invid.',"'.$name.'","'.$phone.'","'.$email.'","'.$address.'")';


    $result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Create investor info succesfully!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    