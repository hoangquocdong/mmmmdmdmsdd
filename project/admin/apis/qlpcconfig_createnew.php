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
        'content'=> 'Create pc fail!'
    );

    $data = json_decode($info);

    $email = clean_text($data ->{'email'});
    $operator = clean_text($data ->{'operator'});
    $pcname = clean_text($data ->{'pcname'});
    $pcid = (int)clean_text($data ->{'pcid'});
    $phone = clean_text($data ->{'phone'});
    $status = (int)clean_text($data ->{'status'});
    $id_orderby = (int)clean_text($data ->{'pcidorderby'});

    $register_date=date("d/m/y H:i:s"); 

    $currentpos = 'csdl,csct,0,60,96471056,MK,';  

    $sql = 'SELECT `id_pwc` FROM `power_company` WHERE 1 ORDER BY `ID` DESC LIMIT 0,1';
    $result = mysql_query($sql) or die(json_encode($returnarray));

    while($row = mysql_fetch_array($result)){
        $pcid = $row['id_pwc']+1;
    }

    $sql = 'INSERT INTO `power_company`(`id_pwc`,`name_pwc`, `name_operator_pc`, 
                        `phone_operator_pc`, `email_operator_pc`, `status`, `id_orderby`) 
            VALUES ('.$pcid.',"'.$pcname.'","'.$operator.'","'.$phone.'","'.$email.'",'.$status.','.$id_orderby.')';


    $result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Create pc info succesfully!'
    );

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    