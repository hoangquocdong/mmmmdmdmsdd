<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Max-Age: 1000');
require "libs/config.php";
require "libs/db_functions.php";
require "libs/common_functions.php";
require "libs/custom_functions.php";

$userid =  (int)isset($_REQUEST['userid'])? $_REQUEST['userid'] : 0;
$info =  isset($_REQUEST['info'])? $_REQUEST['info'] : '';

$start = microtime(true);

  CONNECT_DB();

    mysql_query("SET NAMES utf8");

    $returnarray = array(
        'status' => 500,
        'content'=> 'Create substation fail!'
    );

    $data = json_decode($info);

    $namesub = clean_text($data ->{'namesub'});
    $idsub = (int)clean_text($data ->{'idsub'});
    $idpwc = (int)clean_text($data ->{'idpwc'});
    $idinv = (int)clean_text($data ->{'idinv'});
    $phone = clean_text($data ->{'phone'});
    $email = clean_text($data ->{'email'});
    $addsub = clean_text($data ->{'addsub'});
    $connection = (int)clean_text($data ->{'connection'});
    $ipadd = clean_text($data ->{'ipadd'});
    $voltage = clean_text($data ->{'voltage'});
    $capacity = clean_text($data ->{'capacity'});
    $typesub = (int)clean_text($data ->{'typesub'});
    $gps = clean_text($data ->{'gps'});
    $status = clean_text($data ->{'mdms'});
    $startdate = clean_text($data ->{'startdate'});
    $startdate = $timenow=date("d/m/y H:i:s");

    $sql = 'SELECT `id_sub` FROM `substation_power` WHERE 1 ORDER BY `id_sub` DESC LIMIT 0,1';
    $result = mysql_query($sql) or die(json_encode($returnarray));

    while($row = mysql_fetch_array($result)){
        $idsub = $row['id_sub']+1;
    }


    $sql = 'INSERT INTO `substation_power`(`id_pwc`, `id_investor`, `id_sub`, `name_sub`, 
            `phone_sub`, `email_sub`, `address_sub`, `connection_sub`, `ip_address`, `levelvoltage`, 
            `levelcapacity`, `type_sub`, `gps_sub`, `status`, `startdate`) 
            VALUES ('.$idpwc.','.$idinv.','.$idsub.',"'.$namesub.'",
                "'.$phone.'","'.$email.'","'.$addsub.'",'.$connection.',"'.$ipadd.'","'.$voltage.'",
                "'.$capacity.'",'.$typesub.',"'.$gps.'",'.$status.',"'.$startdate.'")';
    


    // $sql = 'INSERT INTO `investor`(`id_investor`, `name_investor`, 
    //         `phone_investor`, `email_investor`, `adress_investor`)
    //         VALUES ('.$invid.',"'.$name.'","'.$phone.'","'.$email.'","'.$address.'")';


    $result = mysql_query($sql) or die(json_encode($returnarray));

    $returnarray = array(
        'status' => 200,
        'content'=> 'Create sub info succesfully!'
    );

    $actioncode = 8; //create subc profile
    $moredetail = 'new sub : '.$namesub;
    useradminlogs($userid, $actioncode, $moredetail);

    echo json_encode($returnarray); 

    CLOSE_DB();
    unset($sql, $result);
          
?> 

    